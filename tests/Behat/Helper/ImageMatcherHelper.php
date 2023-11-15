<?php

declare(strict_types=1);

namespace Tests\Wemea\SyliusMenuPlugin\Behat\Helper;

use Behat\Mink\Element\NodeElement;
use Webmozart\Assert\Assert;

class ImageMatcherHelper implements ImageMatcherHelperInterface
{
    /**
     * Regex match :  (start with) <default_url_path>/resolve/<filter_name>
     * For example, on : http://127.0.0.1:8000/media/cache/resolve/wemea_sylius_menu_link_icon/a0/bd/6b27c53c6f1fc9f2d136d711ac71.jpeg
     * This regex match: http://127.0.0.1:8000/media/cache/resolve/wemea_sylius_menu_link_icon
     */
    public const LIIP_IMAGINE_PATH_BASE_REGEX = '/^.*\/resolve\/\w+/';

    /** @var array|\ArrayAccess */
    protected $minkParameters;

    /** @var string */
    protected $uploadDirectory;

    public function __construct(
        $minkParameters,
        string $uploadDirectory,
    ) {
        if (!is_array($minkParameters) && !$minkParameters instanceof \ArrayAccess) {
            throw new \InvalidArgumentException(sprintf(
                '"$minkParameters" passed to "%s" has to be an array or implement "%s".',
                self::class,
                \ArrayAccess::class,
            ));
        }
        $this->minkParameters = $minkParameters;

        //add last '/' char if is necessary
        if (!preg_match('/\/$/', $uploadDirectory)) {
            $uploadDirectory .= '/';
        }

        $this->uploadDirectory = $uploadDirectory;
    }

    public function imageMatchToSyliusFixture(string $imageName, string $currentRelativeFilePath): void
    {
        /** @var string|null $fixtureDirectory */
        $fixtureDirectory = $this->getParameter('files_path');
        Assert::notNull($fixtureDirectory);

        $currentFile = $this->uploadDirectory . $currentRelativeFilePath;
        $expectedFile = $fixtureDirectory . $imageName;

        Assert::fileExists(
            $currentFile,
            sprintf("The uploaded file not found on : \n %s", $currentFile),
        );

        Assert::fileExists(
            $expectedFile,
            sprintf("The default file not found on : \n %s", $expectedFile),
        );

        Assert::true(
            md5_file($currentFile) === md5_file($expectedFile),
            'The current file is not the expected file',
        );
    }

    public function imageIsRemoved(?string $baseFilePath): void
    {
        $filePath = $this->uploadDirectory . $baseFilePath;

        Assert::false(
            file_exists($filePath),
            'The file is not removed',
        );
    }

    public function liipImagineGeneratedImageMatchToSyliusFixture(string $imageName, string $absoluteLiipImagePath): void
    {
        //preg_match return 1 if success not true
        Assert::true(
            preg_match(self::LIIP_IMAGINE_PATH_BASE_REGEX, $absoluteLiipImagePath) === 1,
            'The image path not match with liip imagine filter path',
        );
        //remove the base of liip imagine filter path
        $relativePath = preg_replace(self::LIIP_IMAGINE_PATH_BASE_REGEX, '', $absoluteLiipImagePath);

        //make assertion
        $this->imageMatchToSyliusFixture($imageName, $relativePath);
    }

    /**
     * @return NodeElement|string|null
     */
    protected function getParameter(string $name)
    {
        return $this->minkParameters[$name] ?? null;
    }
}
