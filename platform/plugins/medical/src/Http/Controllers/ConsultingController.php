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
use Illuminate\Support\Facades\Validator;
use Botble\Medical\Models\Consulting;
use RvMedia;
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
    
        $data['consulting']->file=RvMedia::getImageUrl($data['consulting']->file,null, false,false);

        page_title()->setTitle(trans('plugins/medical::medical.consulting-details'));
        return view('plugins/medical::consulting.consulting_page')->with($data);
          
     }


     public function setConsulting(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'con_type'=>'required|string',
             'specialty_id'=>'required|string',
             'doctor_id'=>'required|string',
             'p_name'=>'required|string',
             'p_sex'=>'required|string',
             'p_age'=>'required|string',
             'female_status'=>'nullable|string',
             'chronic_diseases'=>'nullable|string',
             'operations'=>'nullable|string',
             'medicines'=>'nullable|string',
             'desc_situation'=>'nullable|string',
             'user_id'=>'required|integer',   
             'file'=>'nullable'
           ]);
          
         if ($validator->fails()) {
             $this->response['error'] = true;
             $this->response['message'] = $validator->errors()->first();
             $this->response['data']=[];
            
         }
         else{
             if(isset($request->file)&&!empty($request->file)){
                 $file= RvMedia::handleUpload($request->file('file'), 0, 'consulting');
                 if(!$file['error']&&!empty($_FILES['file']['name']) && isset($_FILES['file']['name'])){
                     $file=$file['data']['url'];
                 }  
             }
             $data=$request->input();
             $data['file']=isset($file)&&!empty($file)?$file:null;
             Consulting::create((array)$data);

             unset($data['uploaded_file']);
             $this->response['error'] = false;
             $this->response['message'] = "Consulting added successfully!";
             $this->response['data']=$data;
         }
        
         return response()->json($this->response);
     }


}
