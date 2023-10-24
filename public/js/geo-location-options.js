async function initializeSelect2(element, options, onSelectCallback) {
    let select2Config = { ...options };  // Destructuring to avoid modifying the original options object
    
    if (element == "#country_code" && currentUserPhoneCode) {
        delete select2Config.ajax;
    }

    if (select2Config.dataLoader && typeof select2Config.dataLoader === 'function') {
        let data = await select2Config.dataLoader(); // load data asynchronously
        select2Config.data = data;
    }
    
    if (select2Config.data && select2Config.data.length > 0) {
        delete select2Config.ajax; // We remove the AJAX source if custom data is provided
    }
    $(element).select2(select2Config).on('select2:select', onSelectCallback);

    // Hide all containers
    toggleElements([
        '.provinceContainer', '.statesContainer', '.cityContainer', 
        '.indoCityContainer', '.districtContainer', '.subdistrictContainer'
    ], 'hide');

    // Reset all displayed results
    resetTextElements(['#resultStates', '#resultCity', '#resultPropinsi', '#resultDistrict', '#resultSubdistrict']);

    // Reset all select2 values
    resetSelectElements(['#country', '#states', '#city', '#province', '#indoCity', '#district', '#subdistrict']);
}

const select2Configs = {
    '#country_code': {
        theme: 'bootstrap-5',
        language: "id",
        templateResult: formatCountryCode,
        templateSelection: formatCountryCode,
        ajax: {
            'url': 'https://www.supaskateboarding.com/api/countries',
            'dataType': 'json',
            data: function (params) {
                return {
                    term: params.term
                };
            },
            "method": "GET",
            processResults: function (result) {
                $("#states").val('').trigger('change');
                return {
                    results: $.map(result.data[0], function (item) {
                        return {
                            id: item.phonecode,
                            text: item.name,
                            phone: item.phonecode,
                            flag: item.iso2
                        }
                    })
                }
            }
        },
        dataLoader: async function() {
            return new Promise((resolve, reject) => {
                if (currentUserPhoneCode) { // Load only if currentUserPhoneCode is available
                    $.ajax({
                        url: "https://www.supaskateboarding.com/api/countries",
                        method: 'GET',
                        dataType: 'json',
                        success: function(result) {
                            const allCountries = $.map(result.data[0], function(item) {
                                return {
                                    id: item.phonecode,
                                    text: item.name,
                                    phone: item.phonecode,
                                    flag: item.iso2
                                };
                            });
                            resolve(allCountries);
                        },
                        error: function(err) {
                            reject(err);
                        }
                    });
                } else {
                    resolve([]); // Resolve with empty array if currentUserPhoneCode is not available
                }
            });
        },
        onSelect: function(e) {
            const selectedValue = $(e.target).val();
            if (selectedValue === currentUserPhoneCode) {
                // Handle selection logic if necessary
            }
        }
    },
    '#country': {
        theme: 'bootstrap-5',
        language: "id",
        dataType: 'json',
        ajax: {
            'url': 'https://www.supaskateboarding.com/api/countries',
            'dataType': 'json',
            data: function (params) {
                return {
                    term: params.term
                };
            },
            "method": "GET",
            processResults: function (result) {
                $("#states").val('').trigger('change');
                return {
                    results: $.map(result.data[0], function (item) {
                        return {
                            id: item.iso2,
                            text: item.name,
                            phone: item.phonecode,
                            flag: item.iso2
                        }
                    })
                }
            }
        },
        onSelect: function(result) {
            // NOTE: lanjut disini, tambahkan logic untuk menampilkan propinsi, kecamatan, dan kelurahan
            var response = result.params.data;
            // ... if id is 'id' hide statesContainer and cityContainer class
            if (response.id == 'ID') {
                handleIndonesiaSelected();
            } else {
                handleOtherCountrySelected();
            }
        }
    },
    '#states': {
        theme: 'bootstrap-5',
        language: "id",
        placeholder: 'Choose States',
        ajax: {
            "url": "https://www.supaskateboarding.com/api/states",
            "async": true,
            "method": "GET",
            data: function (params) {
                return {
                    country_code : $( '#country' ).find(':selected').val(),
                    q : params.term
                };
            },
            processResults: function (result) {
                $("#city").val('').trigger('change');
                return {
                    results: $.map(result.data[0], function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        }
                    })
                }
            }
        },
        onSelect: function(result) {
            var response = result.params.data;
            $('.cityContainer').show();
            $('#resultKecamatanContainer').hide();
            $('#resultKelurahanContainer').hide();
            $('#resultStates').text(response.text);
        }
    },
    '#city': {
        theme: 'bootstrap-5',
        language: "id",
        placeholder: 'Choose city',
        ajax: {
            "url": "https://www.supaskateboarding.com/api/cities-by-country-state-id",
            "async": true,
            "method": "GET",
            data: function(params) {
                return {
                    term : params.term,
                    country_code : $('#country').find(':selected').val(),
                    states_id : $('#states').find(':selected').val()
                };
            },
            processResults: function (result) {
                return {
                    results: $.map(result.data[0], function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        }
                    })
                }
            }
        },
        onSelect: function(result) {
            var response = result.params.data;
            $('#resultKecamatanContainer').hide();
            $('#resultKelurahanContainer').hide();
            $('#resultCity').text(response.text);
        }
    },
    '#province': {
        theme: 'bootstrap-5',
        language: "id",
        placeholder: 'Choose Propinsi',
        ajax: {
            "url": "https://www.supaskateboarding.com/api/indo-province",
            "async": true,
            "method": "GET",
            data: function(params) {
                return {
                    q : params.term
                };
            },
            processResults: function (result) {
                return {
                    results: $.map(result.data[0], function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        }
                    })
                }
            }
        },
        onSelect: function(result) {
            var response = result.params.data;
            $('.indoCityContainer').show();
            $('#resultKecamatanContainer').hide();
            $('#resultKelurahanContainer').hide();
            $('#resultPropinsi').text(response.text);
        }
    },
    '#indoCity': {
        theme: 'bootstrap-5',
        language: "id",
        placeholder: 'Choose City',
        ajax: {
            "url": "https://www.supaskateboarding.com/api/indo-city",
            "async": true,
            "method": "GET",
            data: function(params) {
                return {
                    q : params.term,
                    prov_id : $('#province').find(':selected').val()
                };
            },
            processResults: function (result) {
                return {
                    results: $.map(result.data[0], function (item) {
                        return {
                            id: item.id,
                            text: item.name
                        }
                    })
                }
            }
        },
        onSelect: function(result) {
            var response = result.params.data;
            // $('.districtContainer').show();
        }
    }
};

async function initializeElements(select2Configs) {
    for (const [element, config] of Object.entries(select2Configs)) {
        await initializeSelect2(element, config, config.onSelect);

        if (element === '#country_code' && currentUserPhoneCode) {
            $(element).val(currentUserPhoneCode).trigger('change');
        }
    }

    if (country_id) {
        loadUserOriginByUserId(user_id);
    }
}

initializeElements(select2Configs);

function formatCountryCode(state) {
    let country_code;
    state.flag == undefined ? country_code = state.id : country_code = state.flag;
    if (!state.id) {
        return state.text;
    }

    var baseUrl = "/assets/img/flag/";
    var $state = $(
        '<span><img class="img-flag" width="15"/> <span></span><span class="countrycode"></span></span>'
    );

    $state.find("span").text(state.text);
    $state.find("span.countrycode").text(' (' + state.phone + ')');
    $state.find("img").attr("src", baseUrl + "/" + country_code.toLowerCase() + ".png");

    return $state;
};

function handleIndonesiaSelected() {
    toggleElements(['.provinceContainer'], 'show');
    toggleElements(['.statesContainer', '.cityContainer'], 'hide');
    resetSelectElements(['#states', '#city']);
}

function handleOtherCountrySelected() {
    toggleElements(['.statesContainer'], 'show');
    toggleElements(['.cityContainer', '.provinceContainer', '.indoCityContainer', '.districtContainer', '.subdistrictContainer'], 'hide');
    resetSelectElements(['#city', '#states', '#city', '#province', '#indoCity', '#district', '#subdistrict']);
}

function toggleElements(elements, action) {
    elements.forEach(function(element) {
        if (action === 'show') {
            $(element).show();
        } else {
            $(element).hide();
        }
    });
}

function resetSelectElements(elements) {
    elements.forEach(function(element) {
        $(element).val('').trigger('change');
    });
}

function resetTextElements(elements) {
    elements.forEach(function(element) {
        $(element).text('');
    });
}


// Load Data Functions
function loadSelectOption(url, selector, dataMapper, additionalCallback) {
    $.ajax({
        url: url,
        async: true,
        method: "GET"
    }).done(function(response) {
        const data = dataMapper(response);
        const newOption = new Option(data.text, data.id, true, true);

        if (additionalCallback) additionalCallback();
        $(selector).append(newOption).trigger('change');
        $(selector).val(data.id).trigger('change');
    });
}

const configMapping = {
    'country': { url: 'https://www.supaskateboarding.com/api/country/', selector: '#country' },
    'country_code': { url: 'https://www.supaskateboarding.com/api/country/', selector: '#country_code'},
    'states': { url: 'https://www.supaskateboarding.com/api/states?state_id=', selector: '#states', container: '.statesContainer' },
    'city': { url: 'https://www.supaskateboarding.com/api/city/', selector: '#city', container: '.cityContainer' },
    'province': { url: 'https://www.supaskateboarding.com/api/indo-province?prov_id=', selector: '#province', container: '.provinceContainer' },
    'indoCity': { url: 'https://www.supaskateboarding.com/api/indo-city?prov_id=', selector: '#indoCity', container: '.indoCityContainer' }
};

const apiCache = {};

function loadData(type, id, additionalParam, dataMapper, additionalCallback) {
    const config = configMapping[type];
    let url = config.url + id;
    if (additionalParam) url += "&" + additionalParam;
    
    loadSelectOption(url, config.selector, dataMapper, function() {
        apiCache[type] = response;
        if (config.container) $(config.container).slideDown();
        if (additionalCallback) additionalCallback();
    });
}

async function loadUserOriginByUserId(user_id) {
    $.ajax({
        url: '/api/user/origin/' + user_id,
        async: true,
        method: "GET",
    }).done(function(response) {
        const data = response.data;
        
        if (data.country_id == 'ID') {
            loadData('country', data.country_id, null, response => ({ id: data.country_id, text: data.country_name }));
            loadData('province', data.indo_province_id, null, response => ({ id: data.indo_province_id, text: data.indo_province_name }));
            loadData('indoCity', data.indo_city_id, null, response => ({ id: data.indo_city_id, text: data.indo_city_name }));
        } else {
            loadData('country', data.country_id, null, response => ({ id: data.country_id, text: data.country_name }));
            loadData('states', data.state_id, null, response => ({ id: data.state_id, text: data.state_name }));
            loadData('city', data.city_id, null, response => ({ id: data.city_id, text: data.city_name }));
        }
    });
}
