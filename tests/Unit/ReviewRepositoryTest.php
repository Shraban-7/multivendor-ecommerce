<?php

use App\Domain\Review\Models\Review;
use App\Domain\Review\Repositories\Contracts\ReviewRepositoryInterface;
use App\Domain\Review\Repositories\EloquentReviewRepository;
use App\Domain\Review\Services\ReviewService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

uses(TestCase::class);

it('ReviewRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(ReviewRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('getByProduct'))->toBeTrue()
        ->and($reflection->hasMethod('getReportedReviews'))->toBeTrue()
        ->and($reflection->hasMethod('store'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue()
        ->and($reflection->hasMethod('averageRating'))->toBeTrue()
        ->and($reflection->hasMethod('getReportedReviewIds'))->toBeTrue();
});

it('implements ReviewRepositoryInterface', function (): void {
    $repo = new EloquentReviewRepository;
    expect($repo)->toBeInstanceOf(ReviewRepositoryInterface::class);
});

it('ReviewServiceProvider binds correct implementation', function (): void {
    expect(app(ReviewRepositoryInterface::class))->toBeInstanceOf(EloquentReviewRepository::class);
});

it('ReviewService is resolvable from container', function (): void {
    expect(app(ReviewService::class))
        ->toBeInstanceOf(ReviewService::class);
});

it('can mock ReviewRepositoryInterface', function (): void {
    $repo = Mockery::mock(ReviewRepositoryInterface::class);
    $review = new Review(['id' => 1, 'rating' => 5, 'description' => 'Great product']);

    $repo->shouldReceive('findById')->with(1)->once()->andReturn($review);

    expect($repo->findById(1))->toBe($review);
});

it('averageRating returns float from repository mock', function (): void {
    $repo = Mockery::mock(ReviewRepositoryInterface::class);

    $repo->shouldReceive('averageRating')->with(1)->once()->andReturn(4.5);

    expect($repo->averageRating(1))->toBe(4.5);
});

it('getReportedReviews returns collection from repository mock', function (): void {
    $repo = Mockery::mock(ReviewRepositoryInterface::class);

    $repo->shouldReceive('getReportedReviews')->once()->andReturn(new Collection);

    expect($repo->getReportedReviews())->toBeInstanceOf(Collection::class);
});
