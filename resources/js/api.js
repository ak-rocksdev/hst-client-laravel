window.api = function (endpoint, method, data, successCallback, errorCallback) {
    // set default successCallback
    if (successCallback === undefined) {
        successCallback = function (data) {
            console.log(data);
        };
    }
    // set default errorCallback
    if (errorCallback === undefined) {
        errorCallback = function (data) {
            console.log(data);
        };
    }
    $.ajax({
        url: endpoint,
        type: method,
        data: data,
        success: successCallback,
        error: errorCallback
    });
};