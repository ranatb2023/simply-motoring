<x-mail::message>
# Thank You, {{ $data['name'] }}!

We have received your message and will get back to you as soon as possible. 

Here is a summary of what you submitted:

**Service:** {{ str_replace('_', ' ', \Illuminate\Support\Str::title($data['service'])) }}  
**Date:** {{ $data['date'] }}  

**Message:**  
{{ $data['message'] }}

Thanks,<br>
Simply Motoring Team
</x-mail::message>
