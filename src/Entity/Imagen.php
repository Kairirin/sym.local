<?php

namespace App\Entity;

use App\Repository\ImagenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImagenRepository::class)]
class Imagen
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    /**
     * @Assert\File(
     * mimeTypes={"image/jpeg","image/png"},
     * mimeTypesMessage = "Solamente se permiten archivos jpeg o png.")
     */
    private ?string $nombre = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column]
    private ?int $categoria = null;

    #[ORM\Column(nullable: true)]
    private ?int $numVisualizaciones = null;

    #[ORM\Column(nullable: true)]
    private ?int $numLike = null;

    #[ORM\Column(nullable: true)]
    private ?int $numDownload = null;

    #[ORM\Column(nullable: false)]
    private ?string $password = null;

    const RUTA_IMAGENES_PORTFOLIO = 'images/index/portfolio/';
    const RUTA_IMAGENES_GALERIA = 'images/index/gallery/';
    const RUTA_IMAGENES_CLIENTES = 'images/clients/';
    const RUTA_IMAGENES_SUBIDAS = 'images/galeria/';

    public function __construct(string $nombre = "", string $desc = "", int $cat = 1, int $numVis = 0, int $numLikes = 0, int $numDownloads = 0, string $password = "")
    {
        $this->id = null;
        $this->nombre = $nombre;
        $this->descripcion = $desc;
        $this->categoria = $cat;
        $this->numVisualizaciones = $numVis;
        $this->numLike = $numLikes;
        $this->numDownload = $numDownloads;
        $this->password = $password;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): static
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getCategoria(): ?int
    {
        return $this->categoria;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function setCategoria(int $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getNumVisualizaciones(): ?int
    {
        return $this->numVisualizaciones;
    }

    public function setNumVisualizaciones(int $numVis): static
    {
        $this->numVisualizaciones = $numVis;

        return $this;
    }

    public function getNumLike(): ?int
    {
        return $this->numLike;
    }

    public function setNumLike(int $numLike): static
    {
        $this->numLike = $numLike;

        return $this;
    }

    public function getNumDownload(): ?int
    {
        return $this->numDownload;
    }

    public function setNumDownload(int $numDown): static
    {
        $this->numDownload = $numDown;

        return $this;
    }
    public function __toString()
    {
        return $this->getDescripcion();
    }
    public function getUrlPortfolio(): string
    {
        return self::RUTA_IMAGENES_PORTFOLIO . $this->getNombre();
    }
    public function getUrlGaleria(): string
    {
        return self::RUTA_IMAGENES_GALERIA . $this->getNombre();
    }
    public function getUrlClientes(): string
    {
        return self::RUTA_IMAGENES_CLIENTES . $this->getNombre();
    }
    public function getUrlSubidas(): string
    {
        return self::RUTA_IMAGENES_SUBIDAS . $this->getNombre();
    }
}
