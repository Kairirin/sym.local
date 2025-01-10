<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
class Categoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nombre = null;

    /**
     * @var Collection<int, Imagen>
     */
    #[ORM\OneToMany(targetEntity: Imagen::class, mappedBy: 'categoria')]
    private Collection $imagenes;

    public function __construct()
    {
        $this->imagenes = new ArrayCollection();
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

    /**
     * @return Collection<int, Imagen>
     */
    public function getImagenes(): Collection
    {
        return $this->imagenes;
    }

    public function addImagene(Imagen $imagene): static
    {
        if (!$this->imagenes->contains($imagene)) {
            $this->imagenes->add($imagene);
            $imagene->setCategoria($this);
        }

        return $this;
    }

    public function removeImagene(Imagen $imagene): static
    {
        if ($this->imagenes->removeElement($imagene)) {
            // set the owning side to null (unless already changed)
            if ($imagene->getCategoria() === $this) {
                $imagene->setCategoria(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNombre();
    }
}
