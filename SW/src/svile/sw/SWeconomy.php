<?php

/*
 *                _   _
 *  ___  __   __ (_) | |   ___
 * / __| \ \ / / | | | |  / _ \
 * \__ \  \ / /  | | | | |  __/
 * |___/   \_/   |_| |_|  \___|
 *
 * SkyWars plugin for PocketMine-MP & forks
 *
 * @Author: svile
 * @Kik: _svile_
 * @Telegram_Gruop: https://telegram.me/svile
 * @E-mail: thesville@gmail.com
 * @Github: https://github.com/svilex/SkyWars-PocketMine
 *
 * Copyright (C) 2016 svile
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * DONORS LIST :
 * - no one
 * - no one
 * - no one
 *
 */

namespace svile\sw;


use pocketmine\Player;


class SWeconomy
{
    const EconomyAPI = 1;
    const PocketMoney = 2;
    const MassiveEconomy = 3;
    /** @var int */
    private $ver = 0;

    /** @var SWmain */
    private $pg;
    /** @var bool|\pocketmine\plugin\Plugin */
    private $api;

    public function __construct(SWmain $plugin)
    {
        $this->pg = $plugin;
        $api = $this->pg->getServer()->getPluginManager()->getPlugin('EconomyAPI');
        if ($api != false && $api instanceof \pocketmine\plugin\Plugin && $api->getDescription()->getVersion() == '2.0.9' && array_shift($api->getDescription()->getAuthors()) == "\x6f\x6e\x65\x62\x6f\x6e\x65") {
            $this->ver = self::EconomyAPI;
            $this->api = $api;
            return;
        }
        $api = $this->pg->getServer()->getPluginManager()->getPlugin('PocketMoney');
        if ($api != false && $api instanceof \pocketmine\plugin\Plugin && $api->getDescription()->getVersion() == '4.0.1' && array_shift($api->getDescription()->getAuthors()) == "\x4d\x69\x6e\x65\x63\x72\x61\x66\x74\x65\x72\x4a\x50\x4e") {
            $this->ver = self::PocketMoney;
            $this->api = $api;
            return;
        }
        $api = $this->pg->getServer()->getPluginManager()->getPlugin('MassiveEconomy');
        if ($api != false && $api instanceof \pocketmine\plugin\Plugin && $api->getDescription()->getVersion() == '1.0 R3' && array_shift($api->getDescription()->getAuthors()) == "\x45\x76\x6f\x6c\x53\x6f\x66\x74") {
            $this->ver = self::MassiveEconomy;
            $this->api = $api;
            return;
        }
    }

    /**
     * @return bool|\pocketmine\plugin\Plugin
     */
    public function getApi()
    {
        return $this->api;
    }

    /**
     * @param bool $string
     * @return int|string
     */
    public function getApiVersion($string = false)
    {
        switch ($this->ver) {
            case 1:
                if ($string)
                    return 'EconomyAPI';
                return self::EconomyAPI;
                break;
            case 2:
                if ($string)
                    return 'PocketMoney';
                return self::PocketMoney;
                break;
            case 3:
                if ($string)
                    return 'MassiveEconomy';
                return self::MassiveEconomy;
                break;
            default:
                if ($string)
                    return 'Not Found';
                return 0;
                break;
        }
    }

    /**
     * @param Player $player
     * @param int $amount
     * @return bool
     */
    public function addMoney(Player $player, $amount = 0)
    {
        switch ($this->ver) {
            case 1:
                if ($this->api->addMoney($player, $amount, true))
                    return true;
                break;
            case 2:
                if ($this->api->grantMoney($player->getName(), $amount))
                    return true;
                break;
            case 3:
                if ($this->api->payPlayer($player->getName(), $amount))
                    return true;
                break;
            default:
                return false;
                break;
        }
        return false;
    }

    /**
     * @param Player $player
     * @param int $amount
     * @return bool
     */
    public function takeMoney(Player $player, $amount = 0)
    {
        switch ($this->ver) {
            case 1:
                if ($this->api->reduceMoney($player, $amount, true))
                    return true;
                break;
            case 2:
                if ($this->api->grantMoney($player->getName(), -$amount))
                    return true;
                break;
            case 3:
                if ($this->api->takeMoney($player, $amount))
                    return true;
                break;
            default:
                return false;
                break;
        }
        return false;
    }

    /**
     * @param Player $player
     * @return bool|int
     */
    public function getMoney(Player $player)
    {
        switch ($this->ver) {
            case 1:
                if ($money = $this->api->myMoney($player) != false)
                    return $money;
                break;
            case 2:
            case 3:
                if ($money = $this->api->getMoney($player->getName()) != false)
                    return $money;
                break;
            default:
                return false;
                break;
        }
        return false;
    }
}