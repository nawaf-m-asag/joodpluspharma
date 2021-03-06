<?php

namespace Botble\Marketplace\Http\Controllers;

use Botble\Base\Events\BeforeEditContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Forms\FormBuilder;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Marketplace\Forms\WithdrawalForm;
use Botble\Marketplace\Http\Requests\WithdrawalRequest;
use Botble\Marketplace\Repositories\Interfaces\WithdrawalInterface;
use Botble\Marketplace\Tables\WithdrawalTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class WithdrawalController extends BaseController
{
    /**
     * @var WithdrawalInterface
     */
    protected $withdrawalRepository;

    /**
     * @param WithdrawalInterface $withdrawalRepository
     */
    public function __construct(WithdrawalInterface $withdrawalRepository)
    {
        $this->withdrawalRepository = $withdrawalRepository;
    }

    /**
     * @param WithdrawalTable $table
     * @return Factory|View
     * @throws Throwable
     */
    public function index(WithdrawalTable $table)
    {
        page_title()->setTitle(trans('plugins/marketplace::withdrawal.name'));

        return $table->renderTable();
    }

    /**
     * @param int $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $withdrawal = $this->withdrawalRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $withdrawal));

        page_title()->setTitle(trans('plugins/marketplace::withdrawal.edit') . ' "' . $withdrawal->customer->name . '"');

        return $formBuilder->create(WithdrawalForm::class, ['model' => $withdrawal])->renderForm();
    }

    /**
     * @param int $id
     * @param WithdrawalRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, WithdrawalRequest $request, BaseHttpResponse $response)
    {
        $withdrawal = $this->withdrawalRepository->findOrFail($id);

        $withdrawal->fill([
            'status'      => $request->input('status'),
            'images'      => array_filter($request->input('images')),
            'user_id'     => Auth::user()->getKey(),
            'description' => $request->input('description'),
        ]);

        $this->withdrawalRepository->createOrUpdate($withdrawal);

        event(new UpdatedContentEvent(WITHDRAWAL_MODULE_SCREEN_NAME, $request, $withdrawal));

        return $response
            ->setPreviousUrl(route('marketplace.withdrawal.index'))
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
            $withdrawal = $this->withdrawalRepository->findOrFail($id);

            $this->withdrawalRepository->delete($withdrawal);

            event(new DeletedContentEvent(WITHDRAWAL_MODULE_SCREEN_NAME, $request, $withdrawal));

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
            $withdrawal = $this->withdrawalRepository->findOrFail($id);
            $this->withdrawalRepository->delete($withdrawal);
            event(new DeletedContentEvent(WITHDRAWAL_MODULE_SCREEN_NAME, $request, $withdrawal));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
