<?php
namespace Botble\Medical\Http\Controllers;
use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medical\Repositories\Interfaces\ConsultingInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Medical\Tables\ConsultingTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
class ConsultingController extends BaseController
{
    /**
     * @var ConsultingInterface
     */
    protected $consultingRepository;

    /**
     * @param ConsultingInterface $consultingRepository
     */
    public function __construct(ConsultingInterface $consultingRepository)
    {
        $this->consultingRepository = $consultingRepository;
    }

    /**
     * @param ConsultingTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(ConsultingTable $table)
    {
  
        page_title()->setTitle(trans('plugins/medical::medical.consulting'));

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
            $consulting = $this->consultingRepository->findOrFail($id);

            $this->consultingRepository->delete($consulting);

            event(new DeletedContentEvent(CONSULTING_MODULE_SCREEN_NAME, $request, $consulting));

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
            $consulting = $this->consultingRepository->findOrFail($id);
            $this->consultingRepository->delete($consulting);
            event(new DeletedContentEvent(CONSULTING_MODULE_SCREEN_NAME, $request, $consulting));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
    /**
     * @return Factory|View
     */
    public function details($id)
    {
        $data['consulting'] = $this->consultingRepository->findOrFail($id);
    

        page_title()->setTitle(trans('plugins/medical::medical.consulting-details'));
        return view('plugins/medical::consulting.consulting_page')->with($data);
          
     }

}
