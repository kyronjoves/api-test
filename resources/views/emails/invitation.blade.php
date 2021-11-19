@component('mail::message')
# Introduction

Dear {{$data['name']}}, <br>
Please click the button below to register

@component('mail::button', ['url' => 'sample link'])
Invitation Link
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
