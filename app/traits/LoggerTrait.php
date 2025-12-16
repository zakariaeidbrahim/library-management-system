<?php
// app/traits/LoggerTrait.php
// Fournit la méthode $this->log() à n'importe quelle classe qui l'utilise.

trait LoggerTrait {
    
    /**
     * Enregistre un message dans le log global via Monolog.
     * @param string $message Le message à logger.
     * @param string $level Niveau de log (info, warning, error, debug, critical).
     * @param array $context Données supplémentaires à inclure.
     */
    protected function log(string $message, string $level = 'info', array $context = []): void {
        // Accéder au logger global stocké dans $GLOBALS
        $logger = $GLOBALS['logger'] ?? null;

        if ($logger) {
            $level = strtolower($level);
            
            // Dispatcher le message à la méthode Monolog appropriée
            switch ($level) {
                case 'debug':
                    $logger->debug($message, $context);
                    break;
                case 'warning':
                    $logger->warning($message, $context);
                    break;
                case 'error':
                    $logger->error($message, $context);
                    break;
                case 'critical':
                    $logger->critical($message, $context);
                    break;
                case 'info':
                default:
                    $logger->info($message, $context);
                    break;
            }
        }
    }
}