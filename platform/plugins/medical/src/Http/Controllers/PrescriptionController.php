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
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Botble\Medical\Models\Prescriptions;
use RvMedia;
class PrescriptionController extends BaseController
{
    /**
     * @var PrescriptionInterface
     */
    protected $prescriptionRepository;

    /**
     * @param PrescriptionInterface $prescriptionRepository
     */
    public function __construct(PrescriptionInterface $prescriptionRepository)
    {
        $this->prescriptionRepository = $prescriptionRepository;
    }

    /**
     * @param PrescriptionTable $table
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

            event(new DeletedContentEvent(PRESCRIPTIONS_MODULE_SCREEN_NAME, $request, $prescription));

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
            $prescription = $this->prescriptionRepository->findOrFail($id);
            $this->prescriptionRepository->delete($prescription);
            event(new DeletedContentEvent(PRESCRIPTIONS_MODULE_SCREEN_NAME, $request, $prescription));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }

    public function setPrescription(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'user_id'=>'required|integer',  
            'notes'=>'nullable|string',   
            'address_id'=>'required|integer',
            'image_file'=>'required_without:file',
            'file'=>'required_without:image_file'
          ]);
         
        if ($validator->fails()) {
            $this->response['error'] = true;
            $this->response['message'] = $validator->errors()->first();
            $this->response['data']=[];
           
        }
        else{
            if(isset($request->image_file)&&!empty($request->image_file)){
                $image_file= RvMedia::handleUpload($request->file('image_file'), 0, 'prescription');
                if(!$image_file['error']&&!empty($_FILES['image_file']['name']) && isset($_FILES['image_file']['name'])){
                    $request->image_file=$image_file['data']['url'];
                   
                }  
               
            }
            else if(isset($request->file)&&!empty($request->file)){
                $file= RvMedia::handleUpload($request->file('file'), 0, 'prescription');
                if(!$file['error']&&!empty($_FILES['file']['name']) && isset($_FILES['file']['name'])){
                    $request->file=$file['data']['url'];
                }  
            }
            $data=$request->input();
           // dd($data);
            $data['image_file']=isset($request->image_file)&&!empty($request->image_file)?$request->image_file:null;
            $data['file']=isset($request->file)&&!empty($request->file)?$request->file:null;
           
            $prescription = Prescriptions::create((array)$data);
            $this->response['error'] = false;
            $this->response['message'] = "Prescription added successfully!";
            $this->response['data']=$prescription;
        }
       
        return response()->json($this->response);
    }

}
