'use strict';

import './bootstrap.js';
import SparkMD5 from 'spark-md5/spark-md5.js';
import {csrf, $} from "./bootstrap.js";

const fileLoader = $("#file_loader").first();
const sendButton = $("#send_button").first();

/**
 * @var {string} hash
 */
let hash;
/**
 * @var {ArrayBuffer, Blob} file
 */
let file;

/**
 * @param {string} message
 * @constructor
 */
const ShowError = (message) => {
    fileLoader.after(()=>{
        return '<kbd class="import error">' + message +'</kbd>'
    })
}

const ShowInfo = (message) => {
    fileLoader.after(()=>{
        return '<kbd class="import info">' + message +'</kbd>'
    })
}


const isFileNotImported = (hash) => {
    const result = $.get('/api/v1/product/import/check_hash', {hash: hash});
    switch (result.status) {
        case 200:
            return true;
        case !200:
            console.log('check hash error: ' +  result.status);
            return false;
    }
}

const SendFile = () => {
    const data = new FormData();
    data.append('excel', file);
    data.append('hash', hash);
    data.append('_token', csrf);

    const result = $.ajax({
        url: '/api/v1/product/import',
        type: 'POST',
        contentType: false,
        processData: false,
        data: data,
    });

    switch (result.status) {
        case 200:
            return true;
        default:
            console.log('check hash error: ' +  result.status);
            return false;
    }
}

/**
 * @param {ProgressEvent<FileReader>} event *}
 */
const fileReaderHandler = event => {
    const result = event.target.result;
    sendButton.prop('disabled', true);
    hash = "";
    $('.import.error').remove();

    if (!(result instanceof ArrayBuffer)) {
        ShowError('Get hash file error');
        return;
    }

    const h = SparkMD5.ArrayBuffer.hash(result);

    if (isFileNotImported(h)) {
        ShowError('File will be loaded');
        return;
    }
    hash = h;
    ShowInfo('File can be imported');
    sendButton.prop('disabled', false);
}

let loadFile = event => {
    const files = event.target.files;
    if ($.isEmptyObject(files) && files.length < 1) {
        return;
    }
    file = files[0];
    const fileReader = new FileReader();
    fileReader.onloadend = fileReaderHandler;
    fileReader.readAsArrayBuffer(file);
}

fileLoader.on('change', loadFile);

const fileUpload = (e) => {
    $('.import.info').remove();
    if (SendFile()) {
        ShowInfo('File will be sent, check products list');
    }
};

sendButton.on('click', fileUpload);
