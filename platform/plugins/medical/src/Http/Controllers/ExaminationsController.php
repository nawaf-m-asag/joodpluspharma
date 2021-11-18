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

}
