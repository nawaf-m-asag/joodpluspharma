<?php

namespace Botble\App_setting\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\App_setting\Http\Requests\AreaRequest;
use Botble\App_setting\Repositories\Interfaces\AreaInterface;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\App_setting\Tables\AreaTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\App_setting\Forms\AreaForm;
use Botble\Base\Forms\FormBuilder;

class AreaController extends BaseController
{
   
    /**
     * @var AreaInterface
     */
    protected $areaRepository;

    /**
     * @param AreaInterface $areaRepository
     */
    public function __construct(AreaInterface $areaRepository)
    {
        $this->areaRepository = $areaRepository;
    }

    /**
     * @param AreaTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    
    public function index(AreaTable $table)
    {
      
        page_title()->setTitle(trans('plugins/app_setting::app_setting.areas'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/app_setting::app_setting.create'));

        return $formBuilder->create(AreaForm::class)->renderForm();
    }

    /**
     * @param AreaRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(areaRequest $request, BaseHttpResponse $response)
    {
        $area = $this->areaRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(AREA_MODULE_SCREEN_NAME, $request, $area));

        return $response
            ->setPreviousUrl(route('area.index'))
            ->setNextUrl(route('area.edit', $area->id))
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
        $area = $this->areaRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $area));

        page_title()->setTitle(trans('plugins/app_setting::app_setting.edit') . ' "' . $area->name . '"');

        return $formBuilder->create(AreaForm::class, ['model' => $area])->renderForm();
    }

    /**
     * @param int $id
     * @param areaRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, areaRequest $request, BaseHttpResponse $response)
    {
        $area = $this->areaRepository->findOrFail($id);

        $area->fill($request->input());

        $area = $this->areaRepository->createOrUpdate($area);

        event(new UpdatedContentEvent(AREA_MODULE_SCREEN_NAME, $request, $area));

        return $response
            ->setPreviousUrl(route('area.index'))
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
            $area = $this->areaRepository->findOrFail($id);

            $this->areaRepository->delete($area);

            event(new DeletedContentEvent(AREA_MODULE_SCREEN_NAME, $request, $area));

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
            $area = $this->areaRepository->findOrFail($id);
            $this->areaRepository->delete($area);
            event(new DeletedContentEvent(AREA_MODULE_SCREEN_NAME, $request, $area));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
