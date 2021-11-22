<?php
namespace Botble\Medical\Http\Controllers;
use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medical\Repositories\Interfaces\NursingInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Medical\Tables\NursingTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Botble\Medical\Models\Nursing;
use RvMedia;
use Illuminate\Support\Facades\DB;
class NursingController extends BaseController
{
    /**
     * @var PrescriptionInterface
     */
    protected $nursingRepository;

    /**
     * @param NursingInterface $nursingRepository
     */
    public function __construct(NursingInterface $nursingRepository)
    {
        $this->nursingRepository = $nursingRepository;
    }

    /**
     * @param NursingTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(NursingTable $table)
    {
  
        page_title()->setTitle(trans('plugins/medical::medical.nursing'));

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
            $nursing = $this->nursingRepository->findOrFail($id);

            $this->nursingRepository->delete($nursing);

            event(new DeletedContentEvent(NURSING_MODULE_SCREEN_NAME, $request, $nursing));

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
            $nursing = $this->nursingRepository->findOrFail($id);
            $this->nursingRepository->delete($nursing);
            event(new DeletedContentEvent(NURSING_MODULE_SCREEN_NAME, $request, $nursing));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
    /**
     * @return Factory|View
     */
    public function details($id)
    {
        $data['nursing'] = $this->nursingRepository->findOrFail($id);
    

        page_title()->setTitle(trans('plugins/medical::medical.nursing-details'));
        return view('plugins/medical::nursing.nursing_page')->with($data);
          
     }

     public function setNursing(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'p_name'=>'required|string',
             'p_age'=>'required|string',
             'p_sex'=>'required|string',
             'doctor_id'=>'required|string',
             'selected_services'=>'required|string',
             'address'=>'required|string',
             'user_id'=>'required|integer',   
             'attachedFile'=>'nullable'
           ]);
          
         if ($validator->fails()) {
             $this->response['error'] = true;
             $this->response['message'] = $validator->errors()->first();
             $this->response['data']=[];
            
         }
         else{
             if(isset($request->attachedFile)&&!empty($request->attachedFile)){
                 $attachedFile= RvMedia::handleUpload($request->file('attachedFile'), 0, 'nursing');
                 if(!$attachedFile['error']&&!empty($_FILES['attachedFile']['name']) && isset($_FILES['attachedFile']['name'])){
                     $file=$attachedFile['data']['url'];
                 }  
             }
             $data=$request->input();
             $data['attachedFile']=isset($file)&&!empty($file)?$file:null;
             $nursing = Nursing::create((array)$data);
             if(isset($request->selected_services)&&!empty($request->selected_services)){
                $services= explode(",", $request->selected_services);
                foreach ($services as $key => $value) {
                    DB::table('med_selected_services')->insert(['nursing_servicrs_id'=>$nursing->id,'services_id'=>$value]);
                }
             }
             unset($data['uploaded_file']);
             $this->response['error'] = false;
             $this->response['message'] = "Nursing added successfully!";
             $this->response['data']=$data;
         }
        
         return response()->json($this->response);
     }

}
