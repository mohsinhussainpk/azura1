<?php
namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @OA\Schema()
 */
trait UniqueId
{
    /**
     * @ORM\Column(name="unique_id", type="string", length=25, nullable=true)
     *
     * @OA\Property(example="69b536afc7ebbf16457b8645")
     *
     * @var string A unique identifier associated with this record.
     */
    protected $unique_id;

    /**
     * @return mixed
     */
    public function getUniqueId()
    {
        return $this->unique_id;
    }

    /**
     * Generate a new unique ID for this item.
     */
    public function generateUniqueId($force_new = false)
    {
        if (empty($this->unique_id) || $force_new) {
            $this->unique_id = bin2hex(random_bytes(12));
        }
    }
}
