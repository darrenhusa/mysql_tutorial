<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Actors index</title>
</head>
<body>
  <h1>Actors index</h1>

  <ul class="container">
  @foreach($actors as $actor)
    <li>{{ $actor->last_name }}, {{ $actor->first_name }}</li>
  @endforeach
  </ul>

  {{ $actors->links() }}

</body>
</html>
