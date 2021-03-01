<?

use Bitrix\Main\Page\Asset;

$ggrachDebuggerRootPath = str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__ . '/..');
$ggrachPathLogFolder = \realpath('.' . $ggrachDebuggerRootPath . '/logs');

\Bitrix\Main\Loader::registerAutoLoadClasses(null, [
    "\GGrach\BitrixDebugger\Debugger\Debugger" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Debugger/Debugger.php",
    "\GGrach\BitrixDebugger\Debugger\NoticeDebugger" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Debugger/NoticeDebugger.php",
    "\GGrach\BitrixDebugger\Debugger\LogFileDebugger" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Debugger/LogFileDebugger.php",
    
    "\GGrach\BitrixDebugger\Contract\ShowModableContract" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Contract/ShowModableContract.php",
    
    "\GGrach\BitrixDebugger\Configurator\DebuggerConfigurator" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Configurator/DebuggerConfigurator.php",
    "\GGrach\BitrixDebugger\Configurator\DebugBarConfigurator" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Configurator/DebugBarConfigurator.php",
    
    "\GGrach\BitrixDebugger\Cache\RuntimeCache" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Cache/RuntimeCache.php",
    
    "\GGrach\BitrixDebugger\Validator\ShowModeDebuggerValidator" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Validator/ShowModeDebuggerValidator.php",
    
    "\GGrach\BitrixDebugger\Representer\DebugBarRepresenter" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Representer/DebugBarRepresenter.php",
    
    "\GGrach\Writer\FileWriter" => $ggrachDebuggerRootPath . "/src/Writer/FileWriter.php",
    
    "\GGrach\Writer\Contract\WritableContract" => $ggrachDebuggerRootPath . "/src/Writer/Contract/WritableContract.php",
    
    "\GGrach\BitrixDebugger\Events\OnEndBufferContent" => $ggrachDebuggerRootPath . "/src/BitrixDebugger/Events/OnEndBufferContent.php"
]);

$ggrachDebuggerConfigurator = new \GGrach\BitrixDebugger\Configurator\DebuggerConfigurator();
$ggrachDebugBarConfigurator = new \GGrach\BitrixDebugger\Configurator\DebugBarConfigurator();

$ggrachDebuggerConfigurator->setLogPath('error', $ggrachPathLogFolder . '/error.log')
    ->setLogPath('warning', $ggrachPathLogFolder . '/warning.log')
    ->setLogPath('success', $ggrachPathLogFolder . '/success.log')
    ->setLogPath('notice', $ggrachPathLogFolder . '/notice.log');

$GLOBALS["DD"] = new \GGrach\BitrixDebugger\Debugger\Debugger($ggrachDebuggerConfigurator, $ggrachDebugBarConfigurator);

/*
 * code - отображать дебаг-данные в коде
 * debug_bar - отображать дебаг-данные в debug_bar
 */
$GLOBALS["DD"]->getConfiguratorDebugger()->setShowModes(['debug_bar']);

function DD() {
    return $GLOBALS["DD"];
}

if (\GGrach\BitrixDebugger\Validator\ShowModeDebuggerValidator::needShowInDebugBar($ggrachDebugBarConfigurator->getConfiguratorDebugger())) {

    Asset::getInstance()->addJs($ggrachDebuggerRootPath . "/assets/DebugBar/js/initializer.js");
    Asset::getInstance()->addCss($ggrachDebuggerRootPath . '/assets/DebugBar/css/themes/general.css');
    Asset::getInstance()->addCss($ggrachDebuggerRootPath . '/assets/DebugBar/css/themes/' . $ggrachDebugBarConfigurator->getColorTheme() . '/theme.css');

    include __DIR__ . '/../functions.php';
    include __DIR__ . '/../events.php';
}