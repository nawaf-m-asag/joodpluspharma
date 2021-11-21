<?php
namespace Botble\Medical\Http\Controllers;
use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medical\Http\Requests\LaboratoriesRequest;
use Botble\Medical\Repositories\Interfaces\LaboratoriesInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Medical\Tables\LaboratoriesTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Medical\Forms\LaboratoriesForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\Controller;
use Botble\Medical\Models\Laboratories;
class LaboratoriesController extends BaseController
{
    /**
     * @var LaboratoriesInterface
     */
    protected $laboratoriesRepository;

    /**
     * @param LaboratoriesInterface $laboratoriesRepository
     */
    public function __construct(LaboratoriesInterface $laboratoriesRepository)
    {
        $this->laboratoriesRepository = $laboratoriesRepository;
    }

    /**
     * @param LaboratoriesTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(LaboratoriesTable $table)
    {
  
        page_title()->setTitle(trans('plugins/medical::medical.laboratories'));

        return $table->renderTable();
    }
 

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/medical::medical.laboratories-create'));
        return $formBuilder->create(LaboratoriesForm::class)->renderForm();
    }
   
    /**
     * @param LaboratoriesRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(LaboratoriesRequest $request, BaseHttpResponse $response)
    {
        
        $laboratories = $this->laboratoriesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(LABORATORIES_MODULE_SCREEN_NAME, $request, $laboratories));
        return $response
            ->setPreviousUrl(route('laboratories.index'))
            ->setNextUrl('#')
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
      
        $laboratories = $this->laboratoriesRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $laboratories));

        page_title()->setTitle(trans('plugins/medical::medical.laboratories-edit') . ' "' . $laboratories->name . '"');

        return $formBuilder->create(LaboratoriesForm::class, ['model' => $laboratories])->renderForm();
    }

    /**
     * @param int $id
     * @param LaboratoriesRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, LaboratoriesRequest $request, BaseHttpResponse $response)
    {


        $laboratories = $this->laboratoriesRepository->findOrFail($id);

        $laboratories->fill($request->input());

        $this->laboratoriesRepository->createOrUpdate($laboratories);

        event(new UpdatedContentEvent(LABORATORIES_MODULE_SCREEN_NAME, $request, $laboratories));

        return $response
            ->setPreviousUrl(route('laboratories.index'))
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
            $laboratories = $this->laboratoriesRepository->findOrFail($id);

            $this->laboratoriesRepository->delete($laboratories);

            event(new DeletedContentEvent(LABORATORIES_MODULE_SCREEN_NAME, $request, $laboratories));

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
            $laboratories = $this->laboratoriesRepository->findOrFail($id);
            $this->laboratoriesRepository->delete($laboratories);
            event(new DeletedContentEvent(LABORATORIES_MODULE_SCREEN_NAME, $request, $laboratories));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getLaboratories(Request $request)
    {
        $laboratories=Laboratories::select('id','lab_name','phone','email','address',)->where('status','published')->get()->toArray();
        if(!empty($laboratories)){
            $this->response['error'] = false;
            $this->response['message']="Laboratories retrieved successfully!";
            $this->response['data'] = $laboratories;
            
        }
        else{
            $this->response['error'] = false;
            $this->response['message']="Laboratories is empty!";
            $this->response['data'] = [];
        }
        return response()->json($this->response);
    }

}
