<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Distance;
use App\Repositories\BookingRepository;
use Illuminate\Http\Request;

class BookingController extends Controller 
{

    /** @var BookingRepository */
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepo)
    {
        $this->bookingRepository = $bookingRepo;
    }

    public function index(Request $request)
    {
        return $this->getJobs($request);
    }

    public function show($id)
    {
        return $this->getJobById($id);
    }

    public function store(Request $request)
    {
       return $this->bookingRepository->createNewBooking($request);
    }

    public function update($id, Request $request) 
    {
        return $this->bookingRepository->updateJob($id, $request);
    }

    public function acceptJob(Request $request)
    {
        return $this->bookingRepository->acceptJob($request); 
    }

    public function cancelJob(Request $request)
    {
        return $this->bookingRepository->cancelJob($request);
    }

    public function distanceFeed(Request $request)
    {
        return $this->bookingRepository->updateDistance($request);
    }

    private function getJobs(Request $request)
    {
        // logic to get jobs
        if($request->user_id) {
            return $this->bookingRepository->getUsersJobs($request->user_id);
        } 
        else if ($request->isAdminOrSuperAdmin()) {
             return $this->bookingRepository->getAllJobs($request);
        }
    }

    private function getJobById($id)
    {
        return $this->bookingRepository->findJobById($id);
    }

}