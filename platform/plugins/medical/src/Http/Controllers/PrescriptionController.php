<?php
namespace Botble\Medical\Http\Controllers;
use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medical\Repositories\Interfaces\PrescriptionInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Medical\Tables\PrescriptionTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Medical\Forms\ServiceForm;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\Controller;

class PrescriptionController extends BaseController
{
    /**
     * @var PrescriptionInterface
     */
    protected $prescriptionRepository;

    /**
     * @param PrescriptionInterface $serviceRepository
     */
    public function __construct(PrescriptionInterface $prescriptionRepository)
    {
        $this->prescriptionRepository = $prescriptionRepository;
    }

    /**
     * @param serviceTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(PrescriptionTable $table)
    {
  
        page_title()->setTitle(trans('plugins/medical::medical.prescriptions'));

        return $table->renderTable();
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
            $prescription = $this->prescriptionRepository->findOrFail($id);

            $this->prescriptionRepository->delete($prescription);

            event(new DeletedContentEvent(PRESCRIPTIONS_MODULE_SCREEN_NAME, $request, $service));

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
            $service = $this->serviceRepository->findOrFail($id);
            $this->serviceRepository->delete($service);
            event(new DeletedContentEvent(PRESCRIPTIONS_MODULE_SCREEN_NAME, $request, $service));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }



}
