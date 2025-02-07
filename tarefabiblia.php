<?php
namespace Xibo\Custom;

use Xibo\Controller\Task;
use Xibo\Factory\ContainerFactory;
use Xibo\Widget\Provider\DataProviderInterface;
use Xibo\Widget\Provider\WidgetProviderInterface;

class tarefabiblia implements \Xibo\XMR\TaskInterface
{
    public function run()
    {
        // Obtenha uma instância do seu provedor de dados
        $dataProvider = ContainerFactory::create()->get(DataProviderInterface::class);
        $widgetProvider = new provedorbiblia();

        // Execute o método fetchData do seu provedor de dados
        $widgetProvider->fetchData($dataProvider);

        // Log de sucesso (opcional)
        \Xibo\Helper\Log::info('Tarefa de atualização dos ganhadores executada com sucesso.');
    }
}