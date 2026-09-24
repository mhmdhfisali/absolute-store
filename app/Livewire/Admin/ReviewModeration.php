<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewModeration extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = 'all'; // all, pending, approved

    public string $ratingFilter = 'all'; // all, 1, 2, 3, 4, 5

    public array $selectedReviews = [];

    public bool $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'ratingFilter' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingRatingFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        if ($value) {
            $this->selectedReviews = $this->getReviewsQuery()->pluck('id')->map(fn ($id) => (string) $id)->toArray();
        } else {
            $this->selectedReviews = [];
        }
    }

    public function approveReview(int $id): void
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => true]);

        AuditLog::log('APPROVE_REVIEW', "Menyetujui ulasan pembeli #{$review->id} untuk produk {$review->product?->name}");

        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Ulasan Disetujui',
            'message' => 'Ulasan pembeli berhasil disetujui dan kini tampil di etalase produk.',
        ]);
    }

    public function rejectReview(int $id): void
    {
        $review = Review::findOrFail($id);
        $review->update(['is_approved' => false]);

        AuditLog::log('REJECT_REVIEW', "Menolak/menyembunyikan ulasan pembeli #{$review->id}");

        $this->dispatch('show-toast', [
            'type' => 'info',
            'title' => 'Ulasan Disembunyikan',
            'message' => 'Ulasan telah ditolak dan tidak akan ditampilkan ke publik.',
        ]);
    }

    public function deleteReview(int $id): void
    {
        $review = Review::findOrFail($id);
        $review->delete();

        AuditLog::log('DELETE_REVIEW', "Menghapus ulasan pembeli #{$id}");

        $this->dispatch('show-toast', [
            'type' => 'error',
            'title' => 'Ulasan Dihapus',
            'message' => 'Ulasan telah dihapus permanen dari sistem.',
        ]);
    }

    public function bulkApprove(): void
    {
        if (empty($this->selectedReviews)) {
            return;
        }

        Review::whereIn('id', $this->selectedReviews)->update(['is_approved' => true]);

        AuditLog::log('BULK_APPROVE_REVIEW', 'Menyetujui '.count($this->selectedReviews).' ulasan pembeli secara massal');

        $count = count($this->selectedReviews);
        $this->selectedReviews = [];
        $this->selectAll = false;

        $this->dispatch('show-toast', [
            'type' => 'success',
            'title' => 'Persetujuan Massal',
            'message' => "{$count} ulasan berhasil disetujui secara bersamaan.",
        ]);
    }

    public function bulkDelete(): void
    {
        if (empty($this->selectedReviews)) {
            return;
        }

        $count = count($this->selectedReviews);
        Review::whereIn('id', $this->selectedReviews)->delete();

        AuditLog::log('BULK_DELETE_REVIEW', "Menghapus {$count} ulasan pembeli secara massal");

        $this->selectedReviews = [];
        $this->selectAll = false;

        $this->dispatch('show-toast', [
            'type' => 'error',
            'title' => 'Hapus Massal',
            'message' => "{$count} ulasan telah dihapus permanen.",
        ]);
    }

    protected function getReviewsQuery()
    {
        return Review::query()
            ->with(['user', 'product'])
            ->when($this->statusFilter === 'pending', fn ($q) => $q->where('is_approved', false))
            ->when($this->statusFilter === 'approved', fn ($q) => $q->where('is_approved', true))
            ->when($this->ratingFilter !== 'all', fn ($q) => $q->where('rating', (int) $this->ratingFilter))
            ->when($this->search !== '', function ($q) {
                $q->where(function ($query) {
                    $query->where('comment', 'like', '%'.$this->search.'%')
                        ->orWhereHas('user', fn ($uq) => $uq->where('name', 'like', '%'.$this->search.'%')->orWhere('email', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('product', fn ($pq) => $pq->where('name', 'like', '%'.$this->search.'%'));
                });
            })
            ->latest();
    }

    public function render()
    {
        $reviews = $this->getReviewsQuery()->paginate(10);
        $totalReviews = Review::count();
        $pendingReviews = Review::where('is_approved', false)->count();
        $approvedReviews = Review::where('is_approved', true)->count();
        $averageRating = (float) (Review::avg('rating') ?? 5.0);

        return view('livewire.admin.review-moderation', compact(
            'reviews',
            'totalReviews',
            'pendingReviews',
            'approvedReviews',
            'averageRating'
        ))->layout('layouts.app');
    }
}
