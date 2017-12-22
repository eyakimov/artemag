<?php

namespace App\Form\Model;

use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword {

    /**
     * @SecurityAssert\UserPassword(
     *     message = "Wrong value for your current password"
     * )
     */
    protected $oldPassword;

    /**
     * @Assert\Length(
     *     min = 5,
     *     minMessage = "Password should by at least 5 chars long"
     * )
     */
    protected $newPassword;

    /**
     * Get oldPassword
     *
     * @return string 
     */
    public function getOldPassword() {
        return $this->oldPassword;
    }

    /**
     * Set oldPassword
     *
     * @param string $oldPassword
     * @return ChangePassword
     */
    public function setOldPassword($oldPassword) {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    /**
     * Get newPassword
     *
     * @return string 
     */
    public function getNewPassword() {
        return $this->newPassword;
    }

    /**
     * Set newPassword
     *
     * @param string $newPassword
     * @return ChangePassword
     */
    public function setNewPassword($newPassword) {
        $this->newPassword = $newPassword;
        return $this;
    }

}
