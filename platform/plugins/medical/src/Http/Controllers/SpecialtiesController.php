<?php
namespace Botble\Medical\Http\Controllers;
use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medical\Http\Requests\SpecialtiesRequest;
use Botble\Medical\Repositories\Interfaces\SpecialtiesInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Medical\Tables\SpecialtiesTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Medical\Forms\SpecialtiesForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\Controller;
use Botble\Medical\Models\Specialties;
class SpecialtiesController extends BaseController
{
    /**
     * @var specialtiesInterface
     */
    protected $specialtiesRepository;

    /**
     * @param specialtiesInterface $specialtiesRepository
     */
    public function __construct(specialtiesInterface $specialtiesRepository)
    {
        $this->specialtiesRepository = $specialtiesRepository;
    }

    /**
     * @param specialtiesTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(specialtiesTable $table)
    {
  
        page_title()->setTitle(trans('plugins/medical::medical.specialties'));

        return $table->renderTable();
    }
 

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/medical::medical.specialties-create'));
        return $formBuilder->create(SpecialtiesForm::class)->renderForm();
    }
   
    /**
     * @param SpecialtiesRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(SpecialtiesRequest $request, BaseHttpResponse $response)
    {
        
       $specialties = $this->specialtiesRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(SPECIALTIES_MODULE_SCREEN_NAME, $request,$specialties));
        return $response
            ->setPreviousUrl(route('specialties.index'))
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
      
       $specialties = $this->specialtiesRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request,$specialties));

        page_title()->setTitle(trans('plugins/medical::medical.specialties-edit') . ' "' .$specialties->name . '"');

        return $formBuilder->create(SpecialtiesForm::class, ['model' =>$specialties])->renderForm();
    }

    /**
     * @param int $id
     * @param SpecialtiesRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, SpecialtiesRequest $request, BaseHttpResponse $response)
    {


       $specialties = $this->specialtiesRepository->findOrFail($id);

       $specialties->fill($request->input());

        $this->specialtiesRepository->createOrUpdate($specialties);

        event(new UpdatedContentEvent(SPECIALTIES_MODULE_SCREEN_NAME, $request,$specialties));

        return $response
            ->setPreviousUrl(route('specialties.index'))
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
            $specialties = $this->specialtiesRepository->findOrFail($id);

            $this->specialtiesRepository->delete($specialties);

            event(new DeletedContentEvent(SPECIALTIES_MODULE_SCREEN_NAME, $request, $specialties));

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
            $specialties = $this->specialtiesRepository->findOrFail($id);
            $this->specialtiesRepository->delete($specialties);
            event(new DeletedContentEvent(SPECIALTIES_MODULE_SCREEN_NAME, $request, $specialties));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function getSpecialties(Request $request){
        $specialties=Specialties::select('id','name')->where('status','published')->get()->toArray();
        if(!empty($specialties)){
            $this->response['error'] = false;
            $this->response['message']="Specialties retrieved successfully!";
            $this->response['data'] = $specialties;
            
        }
        else{
            $this->response['error'] = false;
            $this->response['message']="Specialties is empty!";
            $this->response['data'] = [];
        }
        return response()->json($this->response);
    }



}
