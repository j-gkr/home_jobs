<?php


namespace App\Twig;


use App\Entity\HomeJob;
use App\Entity\Payment;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class ActivityExtension
 *
 * @package App\Twig
 */
class ActivityExtension extends AbstractExtension
{

    /**
     * @var Environment
     */
    private $environment;

    /**
     * ActivityExtension constructor.
     *
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('renderActivity', [$this, 'renderActivity']),
        ];
    }

    /**
     * @param array $activity
     *
     * @return string
     */
    public function renderActivity(array $activity): string
    {
        try {
            switch($activity['objectClass'])
            {
                case Payment::class:
                    $classAlias = 'payment';
                    break;
                case HomeJob::class:
                    $classAlias = 'homeJob';
                    break;
                default:
                    return '';
            }

            return $this->environment->render(sprintf('partials/activity/%s/%s.html.twig', $classAlias, $activity['action']), ['activity' => $activity]);

        } catch (LoaderError $e) {
            return '';
        } catch (RuntimeError $e) {
            return '';
        } catch (SyntaxError $e) {
            return '';
        }
    }
}