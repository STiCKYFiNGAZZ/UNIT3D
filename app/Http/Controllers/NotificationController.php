<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Toastr;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * @var Toastr
     */
    private $toastr;

    /**
     * NotificationController Constructor.
     *
     * @param Toastr $toastr
     */
    public function __construct(Toastr $toastr)
    {
        $this->toastr = $toastr;
    }

    /**
     * Show All Notifications.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        $notification = auth()->user()->notifications()->paginate(25);

        return view('notification.notifications', ['notification' => $notification]);
    }

    /**
     * Set A Notification To Read.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function read($id)
    {
        auth()->user()->unreadNotifications()->findOrFail($id)->markAsRead();

        return redirect()->route('get_notifications')
            ->with($this->toastr->success('Notification Marked As Read!', 'Yay!', ['options']));
    }

    /**
     * Mass Update All Notification's To Read.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function massRead()
    {
        $current = new Carbon();
        auth()->user()->unreadNotifications()->update(['read_at' => $current]);

        return redirect()->route('get_notifications')
            ->with($this->toastr->success('All Notifications Marked As Read!', 'Yay!', ['options']));
    }

    /**
     * Delete A Notification.
     *
     * @param $id
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        auth()->user()->notifications()->findOrFail($id)->delete();

        return redirect()->route('get_notifications')
            ->with($this->toastr->success('Notification Deleted!', 'Yay!', ['options']));
    }

    /**
     * Mass Delete All Notification's.
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        auth()->user()->notifications()->delete();

        return redirect()->route('get_notifications')
            ->with($this->toastr->success('All Notifications Deleted!', 'Yay!', ['options']));
    }
}
