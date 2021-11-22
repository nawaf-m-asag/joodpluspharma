<?php
namespace Botble\Medical\Http\Controllers;
use Assets;
use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Medical\Repositories\Interfaces\ExaminationsInterface;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Botble\Medical\Tables\ExaminationsTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Botble\Medical\Models\Examinations;
use RvMedia;
class ExaminationsController extends BaseController
{
    /**
     * @var ExaminationsInterface
     */
    protected $examinationsRepository;

    /**
     * @param ExaminationsInterface $examinationsRepository
     */
    public function __construct(ExaminationsInterface $examinationsRepository)
    {
        $this->examinationsRepository = $examinationsRepository;
    }

    /**
     * @param ExaminationsTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(ExaminationsTable $table)
    {
  
        page_title()->setTitle(trans('plugins/medical::medical.examinations'));

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
            $examinations = $this->examinationsRepository->findOrFail($id);

            $this->examinationsRepository->delete($examinations);

            event(new DeletedContentEvent(EXAMINATIONS_MODULE_SCREEN_NAME, $request, $examinations));

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
            $examinations = $this->examinationsRepository->findOrFail($id);
            $this->examinationsRepository->delete($examinations);
            event(new DeletedContentEvent(EXAMINATIONS_MODULE_SCREEN_NAME, $request, $examinations));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
    /**
     * @return Factory|View
     */
    public function details($id)
    {
        $data['examinations'] = $this->examinationsRepository->findOrFail($id);
    

        page_title()->setTitle(trans('plugins/medical::medical.examinations-details'));
        return view('plugins/medical::examinations.examinations_page')->with($data);
          
     }


     public function setExamination(Request $request)
     {
         $validator = Validator::make($request->all(), [
             'p_name'=>'required|string',
             'p_sex'=>'required|string',
             'p_age'=>'required|string',
             'address'=>'required|string',
             'lab_id'=>'required|string',
             'required_checks'=>'required|string',
             'd_name'=>'required|string',
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
             Examinations::create((array)$data);

             unset($data['uploaded_file']);
             $this->response['error'] = false;
             $this->response['message'] = "Examination added successfully!";
             $this->response['data']=$data;
         }
        
         return response()->json($this->response);
     }
}
