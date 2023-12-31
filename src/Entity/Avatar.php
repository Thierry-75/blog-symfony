<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



#[ORM\Entity(repositoryClass: AvatarRepository::class)]
#[Vich\Uploadable]
class Avatar 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'avatars', fileNameProperty: 'imageName', size: 'imageSize')]

    #[Assert\Image(
        mimeTypes: ["image/png", "image/gif"],
        minWidth: 64,
        maxWidth: 64,
        minHeight: 64,
        maxHeight: 64,
        maxWidthMessage: "max 64px",
        minWidthMessage: "min 64 px",
        maxHeightMessage: "max 64px",
        minHeightMessage:"min 64 px",
        mimeTypesMessage: "format png, gif",
        allowLandscape: false,
        allowLandscapeMessage: "Only square portrait",
        allowPortrait: true,
        allowSquare: true,
    )]
    #[Assert\File(maxSize: 2048,maxSizeMessage: "limit size 2048 ko")]
    protected ?File $imageFile = null;


    #[ORM\Column(nullable: true)]
    protected ?string $imageName = null;

    #[assert\File(maxSize: "4096k")]
    #[ORM\Column(nullable: true)]
    protected ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the filegetId is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }


}
