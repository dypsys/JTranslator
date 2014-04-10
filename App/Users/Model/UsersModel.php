<?php
/**
 * @package		Translator
 * @copyright	Copyright Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Carles Serrats <carless@cesigrup.com> - http://www.cesigrup.com
 */

namespace App\Users\Model;

use Translator\Authentication\Database\TableUsers;
use Translator\Model\AbstractTranslatorListModel;

/**
 * Model to get data for the groups list view
 *
 * @since  1.0
 */
class UsersModel extends AbstractTranslatorListModel
{
    /**
     * Method to get a DatabaseQuery object for retrieving the data set from a database.
     *
     * @return  DatabaseQuery  A DatabaseQuery object to retrieve the data set.
     *
     * @since   1.0
     */
    protected function getListQuery()
    {
        // $projectId = $this->getProject()->project_id;

        $db    = $this->getDb();
        $query = $db->getQuery(true);

        $table = new TableUsers($db);

        $query->select('tbl.*')
            ->from($db->quoteName($table->getTableName(), 'tbl'))
            ;

        return $query;
    }
}