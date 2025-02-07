<?php
namespace Xibo\Custom;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Xibo\Widget\Provider\DataProviderInterface;
use Xibo\Widget\Provider\DurationProviderInterface;
use Xibo\Widget\Provider\WidgetProviderInterface;
use Xibo\Widget\Provider\WidgetProviderTrait;

class provedorbiblia implements WidgetProviderInterface
{
    use WidgetProviderTrait;

    // Token Bearer para autenticação na API da Bíblia
    private $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdHIiOiJXZWQgSmFuIDI5IDIwMjUgMDE6MDg6MDcgR01UKzAwMDAuaGVucmlxdWVsdWNhc2Rlc291c2FAZ21haWwuY29tIiwiaWF0IjoxNzM4MTEyODg3fQ.yRHH4cO4z4opTZt_pKb4izBkYuKfMFe2rX9oxMYOp4Y';

    public function fetchData(DataProviderInterface $dataProvider): WidgetProviderInterface
    {
        // Criando uma instância do cliente HTTP (Guzzle)
        $client = new Client();

        // Gerando um livro, capítulo e versículo aleatórios
        $livro = 'sl'; // Salmos (poderia ser aleatório)
        $capitulo = 1; // Salmos tem 150 capítulos
        $versiculo = 1; // Assumindo que cada capítulo tem até 20 versículos

        // URL da API para buscar o versículo
        $url = "https://www.abibliadigital.com.br/api/verses/nvi/{$livro}/{$capitulo}/{$versiculo}";

        // Realizando a requisição GET para a API com o token Bearer
        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ]);

        // Decodificando a resposta JSON da API
        $data = json_decode($response->getBody()->getContents(), true);

        // Verificando se a requisição foi bem-sucedida
        if ($data && is_array($data)) {
            // Extraindo os dados do versículo
            $texto = $data['text'] ?? 'N/A';
            $livroNome = $data['book']['name'] ?? 'N/A';
            $capituloNumero = $data['chapter'] ?? 'N/A';
            $versiculoNumero = $data['number'] ?? 'N/A';

            // Adicionando os dados ao provider
            $dataProvider->addItem([
                'texto' => $texto . " - " . $capituloNumero . ":" . $versiculoNumero,
                'livro' => $livroNome,
                'date' => Carbon::now(),
                'createdAt' => Carbon::now(),
            ]);
        }

        // Marcando que os dados foram processados
        $dataProvider->setIsHandled();

        return $this;
    }

    public function fetchDuration(DurationProviderInterface $durationProvider): WidgetProviderInterface
    {
        return $this;
    }

    public function getDataCacheKey(DataProviderInterface $dataProvider): ?string
    {
     
        return null;
    }

    public function getDataModifiedDt(DataProviderInterface $dataProvider): ?Carbon
    {
        // Atualize o cache a cada 5 minutos, sincronizando com o início de cada intervalo.
        return null;
    }
}