<?php
require_once 'vendor/autoload.php';
include('src1/pChart/pData.php');
include('src1/pChart/pChart.php');

use Faker\Factory as FakerFactory;
use GuzzleHttp\Client;

class BookStatistics
{
    private $faker;
    private $client;

    public function __construct()
    {
        $this->faker = FakerFactory::create();
        $this->client = new Client();
    }

    public function generateFixtures(int $count): array
    {
        $genres = [
            'Science Fiction', 'Fantasy', 'Mystery', 'Romance', 'Thriller', 
            'Non-Fiction', 'Biography', 'Self-Help', 'Historical', 'Horror'
        ];

        $fixtures = [];
        for ($i = 0; $i < $count; $i++) {
            $fixtures[] = [
                'title' => $this->faker->sentence(3),
                'author' => $this->faker->name,
                'genre' => $genres[array_rand($genres)],
                'year' => $this->faker->year,
                'rating' => $this->faker->randomFloat(1, 1, 5)
            ];
        }

        return $fixtures;
    }

    public function calculateAverageRatingByGenre(array $fixtures): array
    {
        $ratingByGenre = [];
        foreach ($fixtures as $fixture) {
            $genre = $fixture['genre'];
            $ratingByGenre[$genre][] = $fixture['rating'];
        }

        $averageRatingByGenre = [];
        foreach ($ratingByGenre as $genre => $ratings) {
            $averageRatingByGenre[$genre] = array_sum($ratings) / count($ratings);
        }

        return $averageRatingByGenre;
    }

    public function calculateCountsByField(array $fixtures, string $field): array
    {
        $counts = array_count_values(array_column($fixtures, $field));
        return array_slice($counts, 0, 10); // Limit to 10 for brevity
    }

    public function createChart(array $chartConfig): string
    {
        $response = $this->client->post('https://quickchart.io/chart', [
            'json' => [
                'chart' => $chartConfig,
                'width' => 800,
                'height' => 400,
                'format' => 'png'
            ]
        ]);
        return 'data:image/png;base64,' . base64_encode($response->getBody()->getContents());
    }

    public function addWatermark(string $chartImage, string $watermarkText): string
    {
        $image = imagecreatefromstring(base64_decode(str_replace('data:image/png;base64,', '', $chartImage)));
        $width = imagesx($image);
        $height = imagesy($image);

        $fontSize = 50;
        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 50);

        $x = 0;
        $y = 0;
        while ($y < $height) {
            while ($x < $width) {
                imagestring($image, $fontSize, $x, $y, $watermarkText, $textColor);
                $x += imagefontwidth($fontSize) * strlen($watermarkText) + 50;
            }
            $x = 0;
            $y += imagefontheight($fontSize) + 50;
        }

        ob_start();
        imagepng($image);
        $watermarkedImage = ob_get_clean();

        imagedestroy($image);
        return 'data:image/png;base64,' . base64_encode($watermarkedImage);
    }
}

class ChartRenderer
{
    private $statistics;

    public function __construct()
    {
        $this->statistics = new BookStatistics();
    }

    public function render(): void
    {
        $fixtures = $this->statistics->generateFixtures(100);

        $averageRatingByGenre = $this->statistics->calculateAverageRatingByGenre($fixtures);
        $yearCounts = $this->statistics->calculateCountsByField($fixtures, 'year');
        $genreCounts = $this->statistics->calculateCountsByField($fixtures, 'genre');

        $charts = [];
        $charts[] = $this->statistics->createChart([
            'type' => 'bar',
            'data' => [
                'labels' => array_keys($averageRatingByGenre),
                'datasets' => [[
                    'label' => 'Средний рейтинг',
                    'data' => array_values($averageRatingByGenre)
                ]]
            ]
        ]);

        $charts[] = $this->statistics->createChart([
            'type' => 'pie',
            'data' => [
                'labels' => array_keys($yearCounts),
                'datasets' => [[
                    'label' => 'Книги по годам издания',
                    'data' => array_values($yearCounts)
                ]]
            ]
        ]);

        $charts[] = $this->statistics->createChart([
            'type' => 'line',
            'data' => [
                'labels' => array_keys($genreCounts),
                'datasets' => [[
                    'label' => 'Число книг по жанрам',
                    'data' => array_values($genreCounts)
                ]]
            ]
        ]);

        $chartsWithWatermark = array_map(fn($chart) => $this->statistics->addWatermark($chart, 'Kazmin Anton'), $charts);

        
        include 'chart_template.php';
    }
}

$renderer = new ChartRenderer();
$renderer->render();
?>
