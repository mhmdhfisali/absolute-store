<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Review;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductReviews extends Component
{
    public Product $product;

    public int $rating = 5;

    public string $comment = '';

    public bool $canReview = false;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->checkEligibility();
    }

    public function checkEligibility()
    {
        if (! Auth::check()) {
            $this->canReview = false;

            return;
        }

        // Check if user has at least one completed/paid order for this product
        $hasPaidOrder = Transaction::where('payment_status', 'paid')
            ->where('contact_email_or_phone', Auth::user()->email)
            ->whereHas('productItem', function ($query) {
                $query->where('product_id', $this->product->id);
            })
            ->exists();

        // Also check if user already reviewed
        $alreadyReviewed = Review::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->exists();

        $this->canReview = ($hasPaidOrder || Auth::user()->isAdmin()) && ! $alreadyReviewed;
    }

    public function submitReview()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:3|max:500',
        ], [
            'comment.required' => 'Silakan tulis ulasan singkat Anda.',
            'comment.min' => 'Ulasan minimal 3 karakter.',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'rating' => $this->rating,
            'comment' => clean_html($this->comment) ?? strip_tags($this->comment),
            'is_approved' => true,
        ]);

        $this->comment = '';
        $this->canReview = false;

        $this->dispatch('show-toast', message: 'Ulasan Anda berhasil dikirim! Terima kasih.', type: 'success');
        $this->dispatch('play-chime');
    }

    public function render()
    {
        $reviews = Review::where('product_id', $this->product->id)
            ->where('is_approved', true)
            ->with('user')
            ->latest()
            ->paginate(5);

        $avgRating = $this->product->average_rating;
        $totalReviews = $this->product->reviews_count;

        return view('livewire.product-reviews', compact('reviews', 'avgRating', 'totalReviews'));
    }
}
