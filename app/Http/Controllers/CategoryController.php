<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\Category\CreateRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $service;

    public function __construct()
    {
        parent::__construct();

        $this->service = app(CategoryService::class);
    }

    public function index(Request $request)
    {
        $categories = [];

        try {
            $categories = $this->service->list(false);
            $message = $request->get('message');
            return view('category.index', ['top_bar_data' => $this->top_bar_data] + compact('categories', 'message'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('home'))->withErrors(compact('message'));
    }

    public function create()
    {
        return view('category.create', ['top_bar_data' => $this->top_bar_data]);
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->service->create($request->all());

            $message = __('Data created successfully.');
            return redirect(route('category.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'))->withInput();
    }

    public function edit(int $id, Request $request)
    {
        $message = '';

        try {
            $category = $this->service->find($id);

            return view('category.edit', ['top_bar_data' => $this->top_bar_data] + compact('category'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('category.list'))->withErrors(compact('message'));
    }

    public function update(UpdateRequest $request)
    {
        $message = '';

        try {
            $this->service->update($request->get('id'), $request->only([
                'relevance', 'active', 'description'
            ]));

            $message = __('Data updated successfully.');
            return redirect(route('category.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'))->withInput();
    }

    public function destroy(Request $request)
    {
        try {
            $this->service->delete($request->get('id'));

            $message = __('Data deleted successfully.');
            return redirect(route('category.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('category.list'))->withErrors(compact('message'));
    }

}
