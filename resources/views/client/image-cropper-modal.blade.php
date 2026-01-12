<div class="modal" id="imageCropperModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="imageCropperModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addindustyModal">{{ __('labels.crop_image') }}</h5>
                <button type="button" class="close close-modal" data-close_id="imageCropperModal" data-dismiss="modal" onclick="closeModal()" aria-label="Close">
                    <em class="icon ni ni-cross"></em>
                </button>
            </div>
            <div class="modal-body">
                <div id="image_container">
                    <img alt="image" src="" id="crop_image" class="img-responsive" height="auto" width="100%" />
                </div>
                <input type="hidden" id="imageBaseCode">
                <input type="hidden" id="image_type" value="">

                <input type="hidden" id="__previewId">
                <input type="hidden" id="__base64InputId">
                <input type="hidden" id="__fileInputId">
                <input type="hidden" id="__imageHeight">
                <input type="hidden" id="__imageWidth">
                <input type="hidden" id="__aspectRatio">
                <input type="hidden" id="__cropBoxResizable">
                <input type="hidden" id="__cropperZoomable">
                <input type="hidden" id="__cropperZoomOnWheel">
                <input type="hidden" id="__extension">
                <input type="hidden" id="__section" value="admin">

                <div class="clearfix"></div>
                {{-- <div class="progress progress123 mt-3" style="display: none;">
                    <div class="progress-bar bar bar123 bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                </div> --}}
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" class="btn ripple-effect-dark btn-primary imageCropbtn text-uppercase" id="cropButton">{{ __('labels.save') }}</button>
                <button type="button" class="btn ripple-effect-dark btn-light text-uppercase close-modal" data-close_id="imageCropperModal" data-dismiss="modal">{{ __('labels.cancel') }}</button>
            </div>
        </div>
    </div>
</div>
<script  nonce="{{ csp_nonce('script') }}" type="text/javascript">
    function closeModal() {
        $('#uploadImage').val("");
    }
</script>
