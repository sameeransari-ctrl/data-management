<div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('labels.view')}}</h5>
                <a class="close cursor-pointer" data-bs-dismiss="modal" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </a>
            </div>
            <div class="modal-body">
                <div class="row g-sm-3 g-2">
                    <div class="col-sm-6">
                        <h5 class="lead-text mb-2">{{__('labels.title')}}</h5>
                        <p class="card-text text-break">{{$fsnResult['title'] ?? '---'}}</p>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="lead-text mb-2">{{__('labels.udi_no')}}</h5>
                        <p class="card-text text-break">{{$fsnResult['udi_number'] ?? '---'}}</p>
                    </div>
                    <div class="col-sm-6">
                        <h5 class="lead-text mb-2">{{__('labels.product_name')}}</h5>
                        <p class="card-text text-break">{{$fsnResult['product_name'] ?? '---'}}</p>
                    </div>
                    <div class="col-12">
                        <h5 class="lead-text mb-2">{{__('labels.description')}}</h5>
                        <p class="card-text text-break mb-2">{{$fsnResult['notice_description'] ?? '---'}}</p>
                    </div>
                </div>
                <div class="upload-file">
                    <h5 class="lead-text mb-2">{{__('labels.attach_type')}}</h5>
                    <ul class="d-flex g-2 flex-wrap">
                        @if(!empty($fsnResult['upload_file']))
                            @if($fsnResult['attachment_type'] == 'xlsx')
                                <li>
                                    <a download href="{{$fsnResult['upload_file_url']}}" >
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
                            @if($fsnResult['attachment_type'] == 'video')   
                                <li>
                                    <a download href="{{$fsnResult['upload_file_url']}}" >
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
                            @if($fsnResult['attachment_type'] == 'url')
                                <li>
                                    <a download href="{{$fsnResult['upload_file_url']}}" >
                                        <div class="preview-icon-box border rounded box-shadow p-2">
                                            <div class="preview-icon-wrap w-90px h-70 mx-auto">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="50" viewBox="0 0 36 50">
                                                    <g id="Group_12" data-name="Group 12" transform="translate(166 -289)">
                                                      <g id="Group_7" data-name="Group 7" transform="translate(-184 278)">
                                                        <path id="Path_3" data-name="Path 3" d="M49,61H23a5.015,5.015,0,0,1-5-5V16a5.015,5.015,0,0,1,5-5H40.909L54,22.111V56A5.015,5.015,0,0,1,49,61Z" fill="#e3edfc"/>
                                                        <path id="Path_4" data-name="Path 4" d="M54,22.111H44.182a3.3,3.3,0,0,1-3.273-3.333V11s1.841.208,6.955,4.583C52.841,20.1,54,22.111,54,22.111Z" fill="#b7d0ea"/>
                                                        <path id="Path_5" data-name="Path 5" d="M19.03,59A4.984,4.984,0,0,0,23,61H49a4.984,4.984,0,0,0,3.97-2Z" fill="#c4dbf2"/>
                                                      </g>
                                                      <g id="reshot-icon-globe-8CB5W96N2G" transform="translate(-162 315) rotate(-30)">
                                                        <g id="Group_1" data-name="Group 1">
                                                          <path id="Path_1" data-name="Path 1" d="M10,20A10,10,0,1,1,20,10,10.008,10.008,0,0,1,10,20ZM10,.833A9.167,9.167,0,1,0,19.167,10,9.183,9.183,0,0,0,10,.833Z" fill="#755de0"/>
                                                        </g>
                                                        <g id="Group_2" data-name="Group 2" transform="translate(4.267)">
                                                          <path id="Path_2" data-name="Path 2" d="M31.333,20C28.117,20,25.6,15.6,25.6,10S28.117,0,31.333,0s5.733,4.4,5.733,10S34.55,20,31.333,20Zm0-19.167c-2.7,0-4.917,4.117-4.917,9.167s2.2,9.167,4.917,9.167S36.25,15.05,36.25,10,34.033.833,31.333.833Z" transform="translate(-25.6)" fill="#755de0"/>
                                                        </g>
                                                        <g id="Group_3" data-name="Group 3" transform="translate(2.033 4.267)">
                                                          <rect id="Rectangle_19" data-name="Rectangle 19" width="15.933" height="0.833" fill="#755de0"/>
                                                        </g>
                                                        <g id="Group_4" data-name="Group 4" transform="translate(2.033 14.917)">
                                                          <rect id="Rectangle_20" data-name="Rectangle 20" width="15.933" height="0.833" fill="#755de0"/>
                                                        </g>
                                                        <g id="Group_5" data-name="Group 5" transform="translate(0.417 9.583)">
                                                          <rect id="Rectangle_21" data-name="Rectangle 21" width="19.167" height="0.833" fill="#755de0"/>
                                                        </g>
                                                        <g id="Group_6" data-name="Group 6" transform="translate(9.583 0.417)">
                                                          <rect id="Rectangle_22" data-name="Rectangle 22" width="0.833" height="19.167" fill="#755de0"/>
                                                        </g>
                                                      </g>
                                                    </g>
                                                  </svg>
                                            </div>
                                            <span class="preview-icon-name">{{__('labels.url')}}</span>
                                        </div><!-- .preview-icon-box -->
                                    </a>
                                </li>
                            @endif
                        @else
                            <li>
                                ---
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
