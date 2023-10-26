(function() {
    'use strict';

    // DOM elements
    const cancelUploadButton = document.querySelector('#cancel-upload');
    const uploadPhotoButton = document.querySelector('#upload-photo');
    const photoUploadForm = document.querySelector('#photo-upload-form');
    
    // Extracting data from the DOM
    const formActionURL = photoUploadForm.getAttribute('action');
    const method = photoUploadForm.getAttribute('method');
    const csrfToken = document.querySelector('input[name="_token"]').value;

    // Configs
    const dropzoneConfig = {
        url: formActionURL,
        method: method,
        thumbnailWidth: 150,
        thumbnailHeight: 150,
        parallelUploads: 1,
        uploadMultiple: false,
        autoProcessQueue: false,
        dictDefaultMessage: `<span class="text-secondary">
                                Drag and Drop your files here<br />
                                Or Click to select files!<br />
                                .jpg or .png
                            </span>`,
        params: {
            '_token': csrfToken
        },
        previewTemplate: `
            <div class="dz-preview dz-file-preview">
                <div class="dz-image"><img data-dz-thumbnail /></div>
                <div class="dz-details">
                    <div class="dz-size"><span data-dz-size></span></div>
                    <div class="dz-filename"><span data-dz-name></span></div>
                </div>
                <div class="dz-error-message"><span data-dz-errormessage></span></div>
                <button class="dz-cancel" data-dz-remove>Cancel</button> <!-- Your custom cancel button -->
            </div>
        `,
        init() {
            // More configs or initializations here...
        }
    };

    // Initializing Dropzone
    Dropzone.autoDiscover = false;
    const photoDropzone = new Dropzone("#photo-upload-form", dropzoneConfig);
    
    let isProcessing = false;

    // Event bindings
    cancelUploadButton.addEventListener('click', function() {
        photoDropzone.removeAllFiles(true);
        // set the error message to display none
        const responseElement = document.querySelector('#response-photo-upload');
        responseElement.style.display = 'none';
    });

    uploadPhotoButton.addEventListener('click', function(e) {
        e.preventDefault();
        showLoading();
        if (!photoDropzone.files || !photoDropzone.files.length) {
            hideLoading(); // Ensure this function exists in your script.
            console.log('No files to upload');
        }
        if (!isProcessing) {
            isProcessing = true;
            photoDropzone.processQueue();
        }
    });

    // Dropzone Events
    photoDropzone.on("error", function(files, response) {
        isProcessing = false;
        hideLoading(); // Ensure this function exists in your script.
        photoDropzone.files.forEach(file => file.status = "queued");
        if (response.messages) {
            for (const key in response.messages) {
                const messages = response.messages[key];
                for (const message of messages) {
                    let errorHtml = '<div class="alert alert-danger" role="alert">';
                    Object.keys(messages).forEach(function(field) {
                        errorHtml += `${messages[field]}<br>`;
                    });
                    errorHtml += '</div>';

                    const responseElement = document.querySelector('#response-photo-upload');
                    responseElement.innerHTML = errorHtml;
                    responseElement.style.display = 'block';
                }
            }
        }
    });

    // on success
    photoDropzone.on("success", function(file, response) {
        isProcessing = false;
        showLoading();
        photoDropzone.removeAllFiles(true);
        // set submit button disable
        uploadPhotoButton.setAttribute('disabled', 'disabled');
        if (response.messages) {
            for (const key in response.messages) {
                const messages = response.messages;
                let errorHtml = '<div class="alert alert-success" role="alert">';
                errorHtml += `${messages}<br>`;
                errorHtml += '</div>';

                const responseElement = document.querySelector('#response-photo-upload');
                responseElement.innerHTML = errorHtml;
                responseElement.style.display = 'block';
            }
        }

        setTimeout(() => {
            location.reload();
        }, 1000);
    });

})();
