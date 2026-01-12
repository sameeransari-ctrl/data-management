<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">{{__('labels.reviews_&_ratings')}}</h5>
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                <em class="icon ni ni-cross"></em>
            </a>
        </div>
        <div class="modal-body">
          @forelse ($ratingReviewData as $ratingData)
            <div class="customer_review">
                <div class="d-sm-flex justify-content-between align-items-center flex-wrap mb-1">
                    <h6 class="card-title me-2">{{ucwords($ratingData->user->name)}} @if ($ratingData->user->user_type) ({{ucwords($ratingData->user->user_type)}}) @else '' @endif</h6>
                    <span class="fw-medium ">{{convertDateToTz($ratingData->created_at, 'd M Y, h:i A')}}</span>
                </div>
                <ul class="rating">
                   @if($ratingData->rating == 1 || $ratingData->rating == 1.5)
                      <li><em class="icon ni ni-star-fill"></em></li>
                      @if($ratingData->rating == 1.5)
                         <li><em class="icon ni ni-star-half-fill"></em></li>
                      @else
                         <li><em class="icon ni ni-star"></em></li>
                      @endif
                      <li><em class="icon ni ni-star"></em></li>
                      <li><em class="icon ni ni-star"></em></li>
                      <li><em class="icon ni ni-star"></em></li>
                   @endif
                   @if($ratingData->rating == 2 || $ratingData->rating == 2.5)
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                      @if($ratingData->rating == 2.5)
                         <li><em class="icon ni ni-star-half-fill"></em></li>
                      @else
                         <li><em class="icon ni ni-star"></em></li>
                      @endif
                      <li><em class="icon ni ni-star"></em></li>
                      <li><em class="icon ni ni-star"></em></li>
                   @endif
                   @if($ratingData->rating == 3 || $ratingData->rating == 3.5)
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                      @if($ratingData->rating == 3.5)
                         <li><em class="icon ni ni-star-half-fill"></em></li>
                      @else
                         <li><em class="icon ni ni-star"></em></li>
                      @endif
                      <li><em class="icon ni ni-star"></em></li>
                   @endif
                   @if($ratingData->rating == 4 || $ratingData->rating == 4.5)
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                      @if($ratingData->rating == 4.5)
                         <li><em class="icon ni ni-star-half-fill"></em></li>
                      @else
                         <li><em class="icon ni ni-star"></em></li>
                      @endif
                   @endif
                   @if($ratingData->rating == 5)
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                      <li><em class="icon ni ni-star-fill"></em></li>
                   @endif
                </ul>
                <div class="rating-card-description mt-1">
                    <p class="text-muted mb-1 break-word">
                     @if($ratingData->review)
                        @php
                           $strCount = str_word_count($ratingData->review);
                        @endphp
                     @endif
                     @if($strCount <= 15)
                        {{$ratingData->review}}</p>
                     @else
                        {{substr($ratingData->review, 0, 100)}}
                        <span id="dots-{{$ratingData->id}}"></span><span id="more-read-more-{{$ratingData->id}}" class="d-none"> {{$ratingData->review}} </span> <a class="cursor-pointer readMoreBtn" data-id="{{$ratingData->id}}">...{{__('labels.read_more')}}</a></p>
                     @endif
                </div>
             </div>
          @empty
             <p>{{__('labels.no_reviews_and_ratings')}}</p>
          @endforelse
        </div>
    </div>
 </div>
