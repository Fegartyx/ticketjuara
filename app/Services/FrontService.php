<?php

namespace App\Services;

use App\Repositories\Contracts\ICategoryRepository;
use App\Repositories\Contracts\ISellerRepository;
use App\Repositories\Contracts\ITicketRepository;

class FrontService
{
    protected ITicketRepository $ticketRepository;
    protected ICategoryRepository $categoryRepository;
    protected ISellerRepository $sellerRepository;

    public function __construct(ITicketRepository $ticketRepository, ICategoryRepository $categoryRepository, ISellerRepository $sellerRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->categoryRepository = $categoryRepository;
        $this->sellerRepository = $sellerRepository;
    }

    public function getFrontPageData(){
        $categories = $this->categoryRepository->getAllCategories();
        $sellers = $this->sellerRepository->getAllSellers();
        $popularTickets = $this->ticketRepository->getPopularTickets(4);
        $newTickets = $this->ticketRepository->getAllNewTickets();

        return compact('categories', 'sellers', 'popularTickets', 'newTickets');
    }
}
