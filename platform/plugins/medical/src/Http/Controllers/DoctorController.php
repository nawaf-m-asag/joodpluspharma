<?php
namespace Botble\Medical\Http\Controllers;
use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medical\Http\Requests\DoctorRequest;
use Botble\Medical\Repositories\Interfaces\DoctorInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Medical\Tables\DoctorTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Medical\Forms\DoctorForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\Controller;

class DoctorController extends BaseController
{
    /**
     * @var DoctorInterface
     */
    protected $doctorRepository;

    /**
     * @param DoctorInterface $doctorRepository
     */
    public function __construct(DoctorInterface $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    /**
     * @param DoctorTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(DoctorTable $table)
    {
  
        page_title()->setTitle(trans('plugins/medical::medical.doctors'));

        return $table->renderTable();
    }
 

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/medical::medical.doctor-create'));
        return $formBuilder->create(DoctorForm::class)->renderForm();
    }
   
    /**
     * @param DoctorRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(DoctorRequest $request, BaseHttpResponse $response)
    {
        
        $doctor = $this->doctorRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(DOCTOR_MODULE_SCREEN_NAME, $request, $doctor));
        return $response
            ->setPreviousUrl(route('doctors.index'))
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
      
        $doctor = $this->doctorRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $doctor));

        page_title()->setTitle(trans('plugins/medical::medical.doctor-edit') . ' "' . $doctor->name . '"');

        return $formBuilder->create(DoctorForm::class, ['model' => $doctor])->renderForm();
    }

    /**
     * @param int $id
     * @param DoctorRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, DoctorRequest $request, BaseHttpResponse $response)
    {


        $doctor = $this->doctorRepository->findOrFail($id);

        $doctor->fill($request->input());

        $this->doctorRepository->createOrUpdate($doctor);

        event(new UpdatedContentEvent(DOCTOR_MODULE_SCREEN_NAME, $request, $doctor));

        return $response
            ->setPreviousUrl(route('doctors.index'))
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
            $doctor = $this->doctorRepository->findOrFail($id);

            $this->doctorRepository->delete($doctor);

            event(new DeletedContentEvent(DOCTOR_MODULE_SCREEN_NAME, $request, $doctor));

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
            $doctor = $this->doctorRepository->findOrFail($id);
            $this->doctorRepository->delete($doctor);
            event(new DeletedContentEvent(DOCTOR_MODULE_SCREEN_NAME, $request, $doctor));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }



}
