<?php

namespace Botble\Medical\Http\Controllers\Customers;

use Botble\Medical\Repositories\Interfaces\PrescriptionInterface;
use Botble\Media\Services\ThumbnailService;
use EmailHandler;
use Exception;
use File;
use Hash;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OrderHelper;
use Response;
use RvMedia;
use SeoHelper;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Theme;
use Throwable;
use App\Models\City;
use App\Models\Address;
use Botble\Medical\Http\Requests\PrescriptionRequest;
use Botble\Medical\Models\Prescriptions;
use Botble\Base\Http\Responses\BaseHttpResponse;
class PublicController extends Controller
{
    /**
     * @var PrescriptionInterface
     */
    protected $prescriptionRepository;

    /**
     * PublicController constructor.
     * @param PrescriptionInterface $customerRepository
     */
    public function __construct(
        PrescriptionInterface $prescriptionRepository  
    ) {
        $this->prescriptionRepository = $prescriptionRepository;
    }


   
    public function getAddPrescription()
    {
        SeoHelper::setTitle(__('Add Prescription'));

        Theme::breadcrumb()->add(__('Home'), route('public.index'))
            ->add(__('Add Prescription'), route('customer.prescription.create'));
        $cities=City::all();
        return Theme::scope('medical.customers.prescription.create', compact('cities'),
        Theme::getThemeNamespace() . '::views.ecommerce.customers.prescription.create')->render();
    }

    public function getSetPrescription(PrescriptionRequest $request,BaseHttpResponse $response)
    {
      
       $data=[
           'user_id'=>auth('customer')->id(),
           'city_id'=>$request->city_id,
           'name'=>$request->name,
           'mobile'=>$request->phone,
           'address'=>$request->address,
       ];
    $address_id=Address::create($data);

    if(isset($request->image_file)&&!empty($request->image_file)){
        $image_file= RvMedia::handleUpload($request->file('image_file'), 0, 'prescription');
        if(!$image_file['error']&&!empty($_FILES['image_file']['name']) && isset($_FILES['image_file']['name'])){
            $request->image_file=$image_file['data']['url'];
            $prescription['image_file']=isset($request->image_file)&&!empty($request->image_file)?$request->image_file:null;
            
        }  
       
    }
    if(isset($request->file)&&!empty($request->file)){
        $file= RvMedia::handleUpload($request->file('file'), 0, 'prescription');
        if(!$file['error']&&!empty($_FILES['file']['name']) && isset($_FILES['file']['name'])){
            $request->file=$file['data']['url'];
            $prescription['file']=isset($request->file)&&!empty($request->file)?$request->file:null;
        }  
    }
    $prescription['user_id']=auth('customer')->id();
    $prescription['address_id']=$address_id->id;
    $prescription['notes']=isset($request->notes)?$request->notes:null;
    $prescription = Prescriptions::create($prescription);
    return $response
            
            ->setNextUrl(route('customer.prescription.create'))
            ->setMessage(trans('core/base::notices.create_success_message'));
         
    }   

}
