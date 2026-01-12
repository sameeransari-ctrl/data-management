@if($logoClass == 'logo-dark')
<img class="logo-img logo-dark logo-img-xl" src='{{ $logoUrl }}' srcset='{{ $logoUrl }}' alt="{{getAppName()}}-logo-dark" {{$params}}>
@elseif($logoClass == 'logo-small')
    <img class="logo-img logo-small logo-img-small" src="{{ $logoUrl }}" srcset="{{ $logoUrl }}" alt="{{getAppName()}}-logo-small" {{$params}}>
@elseif($logoClass == 'logo-img')
    <img class="logo-img" src="{{ $logoUrl }}" alt="{{getAppName()}}-logo" height="35px" {{$params}}>
@elseif($logoClass == 'logo-dark-inner')
<img class="logo-img logo-dark" src='{{ $logoUrl }}' srcset='{{ $logoUrl }}' alt="{{getAppName()}}-logo-dark-inner" {{$params}}>
@else
    <img class="{{$logoClass}}" src="{{ $logoUrl }}" alt="{{getAppName()}}-logo" {{$params}}>
@endif
