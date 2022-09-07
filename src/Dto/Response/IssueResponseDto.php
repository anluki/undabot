<?php
declare(strict_types=1);

namespace App\Dto\Response;
use Symfony\Component\Serializer\Annotation\SerializedName;

class IssueResponseDto {

    /**
     * @var int
     * @SerializedName("total_count")
     */
    private int $totalCount;
    /**
     * @var bool
     * @SerializedName("incomplete_results")
     */
    private bool $incompleteResults;

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    /**
     * @return bool
     */
    public function isIncompleteResults(): bool
    {
        return $this->incompleteResults;
    }

    /**
     * @param bool $incompleteResults
     */
    public function setIncompleteResults(bool $incompleteResults): void
    {
        $this->incompleteResults = $incompleteResults;
    }




}