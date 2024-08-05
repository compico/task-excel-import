import $ from 'jquery';

const csrf = $('meta[name="csrf-token"]').attr('content');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrf
    }
});

export {$, csrf};
