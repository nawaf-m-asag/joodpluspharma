<?php

namespace Botble\App_setting\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\App_setting\Http\Requests\TimeRequest;
use Botble\App_setting\Repositories\Interfaces\TimeInterface;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\App_setting\Tables\timeTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\App_setting\Forms\TimeForm;
use Botble\Base\Forms\FormBuilder;

class TimeController extends BaseController
{
   
    /**
     * @var TimeInterface
     */
    protected $TimeRepository;

    /**
     * @param TimeInterface $TimeRepository
     */
    public function __construct(TimeInterface $timeRepository)
    {
        $this->timeRepository = $timeRepository;
    }

    /**
     * @param TimeTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    
    public function index(TimeTable $table)
    {
      
        page_title()->setTitle(trans('plugins/app_setting::app_setting.times'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/app_setting::app_setting.create'));

        return $formBuilder->create(TimeForm::class)->renderForm();
    }

    /**
     * @param TimeRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(TimeRequest $request, BaseHttpResponse $response)
    {
        $time = $this->timeRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(TIME_MODULE_SCREEN_NAME, $request, $time));

        return $response
            ->setPreviousUrl(route('time.index'))
            ->setNextUrl(route('time.edit', $time->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $time = $this->timeRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $time));

        page_title()->setTitle(trans('plugins/app_setting::app_setting.edit') . ' "' . $time->name . '"');

        return $formBuilder->create(TimeForm::class, ['model' => $time])->renderForm();
    }

    /**
     * @param int $id
     * @param TimeRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, TimeRequest $request, BaseHttpResponse $response)
    {
        $time = $this->timeRepository->findOrFail($id);

        $time->fill($request->input());

        $time = $this->timeRepository->createOrUpdate($time);

        event(new UpdatedContentEvent(TIME_MODULE_SCREEN_NAME, $request, $time));

        return $response
            ->setPreviousUrl(route('time.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $time = $this->timeRepository->findOrFail($id);

            $this->timeRepository->delete($time);

            event(new DeletedContentEvent(TIME_MODULE_SCREEN_NAME, $request, $time));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $time = $this->timeRepository->findOrFail($id);
            $this->timeRepository->delete($time);
            event(new DeletedContentEvent(TIME_MODULE_SCREEN_NAME, $request, $time));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
