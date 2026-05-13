<x-mail::message>
# New Contact Form Submission

You have received a new message from the contact form.

**Name:** {{ $data['name'] }}  
**Email:** {{ $data['email'] }}  
**Phone:** {{ $data['phone'] }}  
**Date:** {{ $data['date'] }}  
**Service:** {{ str_replace('_', ' ', \Illuminate\Support\Str::title($data['service'])) }}  

**Message:**  
{{ $data['message'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
