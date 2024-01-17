<?php

$txt = "{t:A Nice Sample Song}
{st:Grzegorz Pietrzak}
{key:C}

# Let's start it!
[C]Let's sing this [G]song [Am]together [Em]aloud
[F]It's an [C]example [Dm]with some nice [G]sound

{soc: Chorus}
[Bb]Whenever you [Am7]need to [Bb]format your [Am7]chords
[Dm]The solution to your [F]problems [G]is very close
{eoc}

{comment: Now we recite some text}
Sometimes you write text
And there's no more room for chords

{comment: Sometimes you play music without any words}
[C] [G] [Am] [Em]

You don't know where the chords are? ~ [F] [C]
You don't have to know ~ [G] [G/F#]

{sot: Outro}
E-12---------------------|
B----11-12---------------|
G----------11s13-14------|
D-------------------10-12|
A------------------------|
E------------------------|
{eot}

{comment: The end}
Let's finish this song. [G] It's the end of the show.
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
