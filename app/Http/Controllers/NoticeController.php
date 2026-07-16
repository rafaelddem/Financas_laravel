<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use Illuminate\Http\Request;
use App\Services\NoticeService;

class NoticeController extends Controller
{
    private NoticeService $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = app(NoticeService::class);
    }

    public function index(Request $request)
    {
        try {
            $message = $request->get('message');
            $notices = $this->service->listNotices();

            return view('notice.index', ['top_bar_data' => $this->top_bar_data] + compact('notices', 'message'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('home'))->withErrors(compact('message'));
    }

    public function access(int $id, Request $request)
    {
        try {
            $notice = $this->service->read($id);

            return $notice->link 
                ? redirect($notice->link)
                : redirect(route('notice.list'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('notice.list'))->withErrors(compact('message'));
    }

    public function read(int $id, Request $request)
    {
        try {
            $read = $request->get('read') != 'false';
            $this->service->read($id, $read);

            return redirect(route('notice.list'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('notice.list'))->withErrors(compact('message'));
    }

    public function destroy(Request $request)
    {
        try {
            $this->service->delete($request->get('id'));

            return redirect(route('notice.list'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('notice.list'))->withErrors(compact('message'));
    }
}
