@extends('layouts.client.app')
@section('title', __('labels.product_management'))
@section('content')
<!-- content @s -->
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">{{__('labels.product_details')}}</h3>
                            <nav>
                                <ul class="breadcrumb breadcrumb-pipe">
                                    <li class="breadcrumb-item"><a href="{{route('client.dashboard')}}">{{__('labels.dashboard')}}</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{route('client.product.index')}}">{{__('labels.product_management')}}</a>
                                    </li>
                                    <li class="breadcrumb-item active">{{__('labels.product_details')}}</li>
                                </ul>
                            </nav>
                        </div>
                        <div class="nk-block-head-content">
                            <a href="{{route('client.product.index')}}" class="btn btn-outline-light bg-white d-none d-sm-inline-flex"><em class="icon ni ni-arrow-left"></em><span>{{__('labels.back')}}</span></a>
                            <a href="{{route('client.product.index')}}" class="btn btn-icon btn-outline-light bg-white d-inline-flex d-sm-none"><em class="icon ni ni-arrow-left"></em></a>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="card">
                        <div class="card-inner">
                            <div class="row">
                                <div class="col-xxl-4 col-lg-5">
                                    <div class="product-gallery d-inline-block border-0 mb-lg-0 mb-3">
                                        <img src="{{$product->image_url}}" class="rounded w-max-550px w-100" alt="medic-product">

                                    </div><!-- .product-gallery -->
                                </div>
                                <div class="col-xxl-8 col-lg-7">
                                    <div class="product-info">
                                        <h2 class="product-title">{{ucwords($product->product_name)}}</h2>
                                        <div class="product-rating">
                                            <ul class="rating">
                                                 <li><span id="star-rating" > </span></li>
                                            </ul>
                                            <a data-product_id="{{$product->id}}" class="amount link-primary cursor-pointer @if(count($product->productRatingReview) == 0) disabled-link @endif" id="review-detail" >({{count($product->productRatingReview)}} {{__('labels.reviews')}})</a>
                                        </div><!-- .product-rating -->
                                        <div class="product-excrept text-soft mt-1">
                                            <p class="lead text-break">{{$product->product_description}}</p>
                                        </div>
                                    </div><!-- .product-info -->
                                    <div class="product-meta mt-2">
                                        <div class="fs-18px fw-bold text-secondary mb-1">{{__('labels.product_details')}}</div>
                                        <ul class="d-sm-flex g-2 gx-lg-5 gx-5 flex-wrap">
                                            <li>
                                                <div class="fs-14px text-muted">{{__('labels.udi_di_eudamed_id')}}</div>
                                                <div class="fs-16px fw-medium text-secondary">{{$product->udi_number}}</div>
                                            </li>
                                            <li>
                                                <div class="fs-14px text-muted">{{__('labels.basic_udi_di_eudamed_id')}}</div>
                                                <div class="fs-16px fw-medium text-secondary">{{$basicUdiData->name}}</div>
                                            </li>
                                            <li class="text-center">
                                                <div class="fs-14px text-muted">{{__('labels.class')}}</div>
                                                <div class="fs-16px fw-medium text-secondary">{{$productClass->name}}</div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="product-meta mt-xl-4 mt-3">
                                        <div class="fs-18px fw-bold text-secondary mb-1">{{__('labels.manufacturer_details')}}</div>
                                        <ul class="d-sm-flex g-2 gx-lg-5 gx-5 flex-wrap">
                                            <li>
                                                <div class="fs-14px text-muted">{{__('labels.name')}}</div>
                                                <div class="fs-16px fw-medium text-secondary">{{ucwords($getclientData->name)}}</div>
                                            </li>
                                            <li>
                                                <div class="fs-14px text-muted">{{__('labels.location')}}</div>
                                                <div class="fs-16px fw-medium text-secondary">{{$getclientData->address}}</div>
                                            </li>
                                            <li>
                                                <div class="fs-14px text-muted">{{__('labels.actor_id_srn')}}</div>
                                                <div class="fs-16px fw-medium text-secondary">{{$getclientData->actor_id}}</div>
                                            </li>
                                            <li>
                                                <div class="fs-14px text-muted">{{__('labels.email')}}</div>
                                                <div class="fs-16px fw-medium text-secondary">{{$getclientData->email}}</div>
                                            </li>
                                            <li>
                                                <div class="fs-14px text-muted">{{__('labels.role')}}</div>
                                                <div class="fs-16px fw-medium text-secondary">{{$role->name}}</div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="product-meta mt-2">
                                        <ul class="d-sm-flex flex-wrap g-2">
                                            @if(!empty($product->productFiles))
                                                @foreach($product->productFiles as $pfile)
                                                    @php
                                                    $extention = getFileUrlExtention($pfile->file_url);
                                                    @endphp
                                                    @if(!empty($extention))
                                                        @if(getFileType($extention) == 'xlsx' || getFileType($extention) == 'xls')
                                                            <li>
                                                                <a id="download" href="{{$pfile->file_url}}" download="{{$product->product_name}}_xlsx">
                                                                    <div class="preview-icon-box border rounded box-shadow p-2">
                                                                        <div class="preview-icon-wrap w-90px h-70 mx-auto">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72" class="w-80px h-70">
                                                                                <path d="M50,61H22a6,6,0,0,1-6-6V22l9-11H50a6,6,0,0,1,6,6V55A6,6,0,0,1,50,61Z" style="fill:#36c684" />
                                                                                <path d="M25,20.556A1.444,1.444,0,0,1,23.556,22H16l9-11h0Z" style="fill:#95e5bd" />
                                                                                <path d="M42,31H30a3.0033,3.0033,0,0,0-3,3V45a3.0033,3.0033,0,0,0,3,3H42a3.0033,3.0033,0,0,0,3-3V34A3.0033,3.0033,0,0,0,42,31ZM29,38h6v3H29Zm8,0h6v3H37Zm6-4v2H37V33h5A1.001,1.001,0,0,1,43,34ZM30,33h5v3H29V34A1.001,1.001,0,0,1,30,33ZM29,45V43h6v3H30A1.001,1.001,0,0,1,29,45Zm13,1H37V43h6v2A1.001,1.001,0,0,1,42,46Z" style="fill:#fff" />
                                                                            </svg>
                                                                        </div>
                                                                        <span class="preview-icon-name">{{__('labels.xlsx')}}</span>
                                                                    </div><!-- .preview-icon-box -->
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(getFileType($extention) == 'video')
                                                            <li>
                                                                <a id="download" href="{{$pfile->file_url}}" download="{{$product->product_name}}_video">
                                                                    <div class="preview-icon-box border rounded box-shadow p-2">
                                                                        <div class="preview-icon-wrap w-90px h-70 mx-auto">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72" class="w-80px h-70">
                                                                                <path d="M49,61H23a5.0147,5.0147,0,0,1-5-5V16a5.0147,5.0147,0,0,1,5-5H40.9091L54,22.1111V56A5.0147,5.0147,0,0,1,49,61Z" style="fill:#e3edfc" />
                                                                                <path d="M54,22.1111H44.1818a3.3034,3.3034,0,0,1-3.2727-3.3333V11s1.8409.2083,6.9545,4.5833C52.8409,20.0972,54,22.1111,54,22.1111Z" style="fill:#b7d0ea" />
                                                                                <path d="M19.03,59A4.9835,4.9835,0,0,0,23,61H49a4.9835,4.9835,0,0,0,3.97-2Z" style="fill:#c4dbf2" />
                                                                                <path d="M46,46.5v-13A3.5042,3.5042,0,0,0,42.5,30h-13A3.5042,3.5042,0,0,0,26,33.5v13A3.5042,3.5042,0,0,0,29.5,50h13A3.5042,3.5042,0,0,0,46,46.5ZM40,45v3H37V45Zm-3-2V37h7v6Zm0-8V32h3v3Zm-2-3v3H32V32Zm0,5v6H28V37Zm0,8v3H32V45Zm7.5,3H42V45h2v1.5A1.5016,1.5016,0,0,1,42.5,48ZM44,33.5V35H42V32h.5A1.5016,1.5016,0,0,1,44,33.5ZM29.5,32H30v3H28V33.5A1.5016,1.5016,0,0,1,29.5,32ZM28,46.5V45h2v3h-.5A1.5016,1.5016,0,0,1,28,46.5Z" style="fill:#f74141" />
                                                                            </svg>
                                                                        </div>
                                                                        <span class="preview-icon-name">{{__('labels.video')}}</span>
                                                                    </div><!-- .preview-icon-box -->
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(getFileType($extention) == 'image')
                                                            <li>
                                                                <a id="download" href="{{$pfile->file_url}}" download="{{$product->product_name}}_image">
                                                                    <div class="preview-icon-box border rounded box-shadow p-2">
                                                                        <div class="preview-icon-wrap w-90px h-70 mx-auto">
                                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 72 72" class="w-80px h-70">
                                                                                <path d="M49,61H23a5.0147,5.0147,0,0,1-5-5V16a5.0147,5.0147,0,0,1,5-5H40.9091L54,22.1111V56A5.0147,5.0147,0,0,1,49,61Z" style="fill:#e3edfc" />
                                                                                <path d="M54,22.1111H44.1818a3.3034,3.3034,0,0,1-3.2727-3.3333V11s1.8409.2083,6.9545,4.5833C52.8409,20.0972,54,22.1111,54,22.1111Z" style="fill:#b7d0ea" />
                                                                                <path d="M19.03,59A4.9835,4.9835,0,0,0,23,61H49a4.9835,4.9835,0,0,0,3.97-2Z" style="fill:#c4dbf2" />
                                                                                <path d="M27.2223,43H44.7086s2.325-.2815.7357-1.897l-5.6034-5.4985s-1.5115-1.7913-3.3357.7933L33.56,40.4707a.6887.6887,0,0,1-1.0186.0486l-1.9-1.6393s-1.3291-1.5866-2.4758,0c-.6561.9079-2.0261,2.8489-2.0261,2.8489S25.4268,43,27.2223,43Z" style="fill:#755de0" />
                                                                            </svg>
                                                                        </div>
                                                                        <span class="preview-icon-name">{{__('labels.image')}}</span>
                                                                    </div><!-- .preview-icon-box -->
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="reviewSec mt-lg-4 mt-2">
                               <div class="row g-1 g-sm-5">
                                    <div class="col-md-6">
                                        <h4 class="review_head mb-3"> {{__('labels.questionnaire_for_review')}}</h4>
                                        <h6 class="review_quest mb-2 ">{{__('labels.review_questionnaire')}}</h6>

                                        @forelse ($productQuestionTypeReview as $questionKey => $questionReview)
                                            @php
                                                $answerOptions = !is_null($questionReview['answer_options']) ? json_decode($questionReview['answer_options'], true) : [];
                                            @endphp
                                                <div class="box">
                                                    <div class="form-group mb-0">
                                                        <p class="form-label">{{$questionKey+1}}. {{$questionReview['question_title']}}</p>
                                                        @if($questionReview['answer_type'] == 1)
                                                            <div class="row gy-1 mb-1">
                                                                @forelse ($answerOptions as $options)
                                                                    <div class="col-lg-3 col-6 custom-checkText">
                                                                        <div class="custom-control custom-control-sm custom-checkbox">
                                                                            <input type="checkbox" disabled class="custom-control-input" id="mcqCheck11_1">
                                                                            <p class="custom-control-label" for="mcqCheck11_1">{{$options}}</p>
                                                                        </div>
                                                                    </div>
                                                                @empty
                                                                    <p>{{__('labels.no_option')}}</p>
                                                                @endforelse
                                                        </div>
                                                        @elseif($questionReview['answer_type'] == 2)
                                                            <div class="row gy-1 mb-1">
                                                                @forelse ($answerOptions as $options)
                                                                <div class="col-lg-3 col-6">
                                                                    <div class="form-control-wrap">
                                                                            <div class="custom-control custom-radio me-2">
                                                                                <input type="radio" disabled id="customRadio113_1" name="customRadioMeet" class="custom-control-input">
                                                                                <p class="custom-control-label" for="customRadio113_1">{{$options}}</p>
                                                                            </div>
                                                                        </div>
                                                                 </div>
                                                                @empty
                                                                    <p>{{__('labels.no_option')}}</p>
                                                                @endforelse
                                                            </div>
                                                        @else
                                                            <div class="row">
                                                                 <div class="col-lg-6">
                                                                    <div class="form-control-wrap mb-1">
                                                                        <input type="text" readonly class="form-control shadow-none" placeholder="{{__('labels.enter_text')}}" autocomplete="off">
                                                                    </div>
                                                                 </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    {{-- <p class="reivew_ans">
                                                        <span class="fs-15px fw-bold">Ans:</span>
                                                        @if(!empty($questionReview['answer_options']))
                                                            @forelse ($answerOptions as $key => $answer)
                                                                @if($questionReview['answer_type'] == $key)
                                                                    {{$answer}}
                                                                @endif
                                                            @empty
                                                                <p>No Answers</p>
                                                            @endforelse
                                                        @else
                                                            ---
                                                        @endif
                                                    </p> --}}
                                                </div>
                                        @empty
                                            <p>{{__('labels.no_questions')}}</p>
                                        @endforelse
                                    </div>

                                    <div class="col-md-6">
                                        <h4 class="review_head mb-3">{{__('labels.product_questionnaire')}}</h4>
                                        <h6 class="review_quest mb-2 ">{{__('labels.product_questionnaires')}}</h6>
                                        @forelse ($productQuestionTypeProduct as $productKey => $productReview)
                                            @php
                                                $productAnswerOptions = !is_null($productReview['answer_options']) ? json_decode($productReview['answer_options'], true) : [];
                                            @endphp
                                            <div class="box">
                                                <div class="form-group mb-0">
                                                    <p class="form-label">{{$productKey+1}}. {{$productReview['question_title']}}</p>
                                                    @if($productReview['answer_type'] == 1)
                                                        <div class="row gy-1 mb-1">
                                                            @forelse ($productAnswerOptions as $option)
                                                                <div class="col-lg-3 col-6 custom-checkText">
                                                                    <div class="custom-control custom-control-sm custom-checkbox">
                                                                        <input type="checkbox" disabled class="custom-control-input" id="mcqCheck11_1">
                                                                        <p class="custom-control-label" for="mcqCheck11_1">{{$option}}</p>
                                                                    </div>
                                                                </div>
                                                            @empty
                                                                <p>{{__('labels.no_option')}}</p>
                                                            @endforelse
                                                    </div>
                                                    @elseif($productReview['answer_type'] == 2)
                                                        <div class="row gy-1 mb-1">
                                                            @forelse ($productAnswerOptions as $option)
                                                            <div class="col-lg-3 col-6">
                                                                <div class="form-control-wrap">
                                                                    <div class="custom-control custom-radio me-2">
                                                                        <input type="radio" disabled id="customRadio113_1" name="customRadioMeet" class="custom-control-input">
                                                                        <p class="custom-control-label" for="customRadio113_1">{{$option}}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @empty
                                                                <p>{{__('labels.no_option')}}</p>
                                                            @endforelse
                                                        </div>
                                                    @else

                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-control-wrap mb-1">
                                                                <input type="text" readonly class="form-control shadow-none" placeholder="{{__('labels.enter_text')}}" autocomplete="off">
                                                            </div>
                                                        </div>
                                                   </div>

                                                    @endif
                                                </div>
                                                {{-- <p class="reivew_ans">
                                                    <span class="fs-15px fw-bold">Ans:</span>
                                                    @if(!empty($productReview['answer_options']))
                                                        @forelse ($productAnswerOptions as $key => $answer)
                                                            @if($productReview['answer_type'] == $key)
                                                                {{$answer}}
                                                            @endif
                                                        @empty
                                                            <p>No Answers</p>
                                                        @endforelse
                                                    @else
                                                        ---
                                                    @endif
                                                </p> --}}
                                            </div>
                                        @empty
                                            <p>{{__('labels.no_questions')}}</p>
                                        @endforelse
                                    </div>
                               </div>
                            </div>
                            <div class="text-end mt-lg-4 mt-2">
                                <a href="{{route('client.product.edit', $product->id)}}" type="button" class="btn btn btn-light">{{__('labels.edit')}}</a>
                            </div>
                        </div>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- content @e -->

<!-- review rating modal -->
<div class="modal fade" id="reviewRating" aria-modal="true" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">

</div>

@endsection

@push('scripts')
{!! returnScriptWithNonce(asset_path('assets/js/client/product/raty.min.js')) !!}
{!! returnScriptWithNonce(asset_path('assets/js/client/product/product-details.js')) !!}
<script nonce="{{csp_nonce('script')}}">
    $(document).ready(function() {
        $('#star-rating').raty({readOnly:true, starOn:"{{asset_path('assets/images/star-on.png')}}", starOff:"{{asset_path('assets/images/star-off.png')}}" ,starHalf:"{{asset_path('assets/images/star-half.png')}}" ,score: {{$avgRating}} });

    });
</script>
@endpush

