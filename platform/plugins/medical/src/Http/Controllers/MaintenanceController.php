<?php
namespace Botble\Medical\Http\Controllers;
use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medical\Repositories\Interfaces\MaintenanceInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Medical\Tables\MaintenanceTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Botble\Medical\Models\Maintenance;
use RvMedia;
class MaintenanceController extends BaseController
{
    /**
     * @var MaintenanceInterface
     */
    protected $maintenanceRepository;

    /**
     * @param MaintenanceInterface $maintenanceRepository
     */
    public function __construct(MaintenanceInterface $maintenanceRepository)
    {
        $this->maintenanceRepository = $maintenanceRepository;
    }

    /**
     * @param MaintenanceTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(MaintenanceTable $table)
    {
  
        page_title()->setTitle(trans('plugins/medical::medical.maintenance'));

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
            $maintenance = $this->maintenanceRepository->findOrFail($id);

            $this->maintenanceRepository->delete($maintenance);

            event(new DeletedContentEvent(MAINTENANCE_MODULE_SCREEN_NAME, $request, $maintenance));

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
            $maintenance = $this->maintenanceRepository->findOrFail($id);
            $this->maintenanceRepository->delete($maintenance);
            event(new DeletedContentEvent(MAINTENANCE_MODULE_SCREEN_NAME, $request, $maintenance));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
    /**
     * @return Factory|View
     */
    public function details($id)
    {
        $data['maintenance'] = $this->maintenanceRepository->findOrFail($id);
    
        $data['maintenance']->file=RvMedia::getImageUrl($data['maintenance']->file,null, false,false);
        page_title()->setTitle(trans('plugins/medical::medical.maintenance-details'));
        return view('plugins/medical::maintenance.maintenance_page')->with($data);
          
     }
    public function setMaintenance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'side_name'=>'required|string',
            'applicant_name'=>'required|string',
            'device_name'=>'required|string',
            'descrip_defect'=>'required|string',
            'phone'=>'required|string',
            'user_id'=>'required|integer',   
            'address'=>'required|string',
            'file'=>'nullable'
          ]);
         
        if ($validator->fails()) {
            $this->response['error'] = true;
            $this->response['message'] = $validator->errors()->first();
            $this->response['data']=[];
           
        }
        else{
            if(isset($request->file)&&!empty($request->file)){
                $file= RvMedia::handleUpload($request->file('file'), 0, 'maintenance');
                if(!$file['error']&&!empty($_FILES['file']['name']) && isset($_FILES['file']['name'])){
                    $request->file=$file['data']['url'];
                }  
            }
            $data=$request->input();
            $data['file']=isset($request->file)&&!empty($request->file)?$request->file:null;
           
            $prescription = Maintenance::create((array)$data);
            $this->response['error'] = false;
            $this->response['message'] = "Maintenance added successfully!";
            $this->response['data']=$prescription;
        }
       
        return response()->json($this->response);
    }
}
