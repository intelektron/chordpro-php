<?php

$txt = "{t:Chordpro-PHP Song}
{st:Nicolas Wurtz}
{c:GPL3 2019 Nicolas Wurtz}
{key:C}

# Let's start it!
[C]This is the [Dm]beautiful [Em]song
I [Dm]wrote in [F/G]Chordpro for[C]mat [Dm/F]
Let's sing it a[C/E]long
[Bb] It's ea[Dm]sy to do [F]that [C]

{soc}
[F] [G] [C]This is the refrain
[F] [G] [C]We could sing it twice
{eoc}

{c:Final}
[Em/D]This is the [Bb]end.
";

require __dir__ . '/../vendor/autoload.php';

$parser = new ChordPro\Parser();
$htmlFormatter = new ChordPro\Formatter\HtmlFormatter();
$monospaceFormatter = new ChordPro\Formatter\MonospaceFormatter();
$jsonFormatter = new ChordPro\Formatter\JSONFormatter();

// Parse the song!
$song = $parser->parse($txt);

// Format it!
$html = $htmlFormatter->format($song);
$monospaced = $monospaceFormatter->format($song);
$json = $jsonFormatter->format($song);

// Change notation!
$frenchNotation = new ChordPro\Notation\FrenchChordNotation();
$html_french = $htmlFormatter->format($song, ['notation' => $frenchNotation]);

// Transpose it!
$transposer = new ChordPro\Transposer();
$transposer->transpose($song, 2);
$utfNotation = new ChordPro\Notation\UtfChordNotation();
$html_transposed = $htmlFormatter->format($song, ['notation' => $utfNotation]);

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ChordPro PHP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Test ChordProPHP">
    <meta name="author" content="Nicolas Wurtz">
    <meta name="author" content="Grzegorz Pietrzak">
    <link rel="stylesheet" href="example.css" />
  </head>
  <body>
      <h1>HTML</h1>
      <?php echo $html; ?>
      <h1>HTML - French Notation</h1>
      <?php echo $html_french; ?>
      <h1>HTML - Transposed +2</h1>
      <?php echo $html_transposed; ?>
      <h1>Monospaced</h1>
      <pre><?php echo $monospaced; ?></pre>
      <h1>JSON</h1>
      <pre style="height: 300px; overflow: auto;"><?php echo $json; ?></pre>
  </body>
</html>
