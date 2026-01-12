<!doctype html>
<html>
    <head>
<title>{{__('labels.site_maintenance')}} | {{Str::ucfirst(getAppName())}}</title>
<style>
  body { text-align: center; padding: 150px; }
  h1 { font-size: 50px; }
  body { font: 20px Helvetica, sans-serif; color: #333; }
  article { display: block; text-align: left; width: 650px; margin: 0 auto; }
  a { color: #dc8100; text-decoration: none; }
  a:hover { color: #333; text-decoration: none; }
</style>
</head>
    <body>

<article>
    <h1>{{__('labels.we')}}&rsquo;{{__('labels.ll_be_back_soon')}}</h1>
    <div>
        <p>{{__('labels.sorry_for_the_inconvenience_but_we')}}&rsquo;{{__('labels.re_performing_some_maintenance_at_the_moment_If_you_need_to_you_can_always')}}<a href="mailto:{{config('mail.from.address')}}">{{__('labels.contact_us')}}</a>, {{__('labels.otherwise_we')}}&rsquo;{{__('labels.ll_be_back_online_shortly')}}</p>
        <p>&mdash; {{__('labels.the')}} {{Str::ucfirst(getAppName())}}{{__('labels.team')}}</p>
    </div>
</article>

</body>
</html>
