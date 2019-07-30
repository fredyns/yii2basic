<?= "<?php\n" ?>

namespace <?= $actionNameSpace ?>;

/**
 * Action Access control checks all relevan condition to decide whether an action is executable
 *
 * @author Fredy Nurman Saleh <email@fredyns.net>
 */
class AccessControl extends \app\lib\AccessControl
{

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->passed();
    }

}